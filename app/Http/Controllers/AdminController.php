<?php

namespace App\Http\Controllers;

use App\Models\Attandance;
use App\Models\AttandanceSetting;
use App\Models\User;
use App\Models\Notification;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {

        if (!auth()->user()->is_admin) {
            return redirect()->route('dashboard');
        }

        $totalUsers = User::count();
        $totalAttendance = Attandance::whereHas('device.user')->count();
        $averagePoints = Attandance::whereHas('device.user')->avg('points_earned');
        $recentUsers = User::latest()->take(5)->get();
        $recentAttendance = Attandance::with(['device.user'])
            ->whereHas('device.user')
            ->latest()
            ->take(10)
            ->get();

        $devices = Device::with('user')->where('status', 'registered')->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAttendance',
            'averagePoints',
            'recentUsers',
            'recentAttendance',
            'devices'
        ));
    }

    public function users()
    {
        $users = User::withCount('attendanceRecords')
            ->withAvg('attendanceRecords', 'points_earned')
            ->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();
        return back()->with('success', 'User admin status updated successfully');
    }

    public function dashboardData()
    {
        return [
            'recentUsers' => User::where('is_admin', false)
                ->latest()
                ->take(5)
                ->get(),

            'recentAttendance' => Attandance::with(['device.user' => function($query) {
                $query->where('is_admin', false);
            }])
                ->whereHas('device.user', function($query) {
                    $query->where('is_admin', false);
                })
                ->latest()
                ->take(5)
                ->get(),

            'totalUsers' => User::where('is_admin', false)->count(),
            'totalAttendance' => Attandance::whereHas('device.user', function($query) {
                $query->where('is_admin', false);
            })->count(),
            'averagePoints' => Attandance::whereHas('device.user', function($query) {
                $query->where('is_admin', false);
            })->avg('points_earned') ?? 0,
        ];
    }

    public function devices()
    {
        $devices = Device::with('user')->get();
        $users = User::all();

        return view('admin.devices', compact('devices', 'users'));
    }

    public function registerDevice(Device $device, Request $request)
    {
        $device->update([
            'status' => 'registered',
            'user_id' => $request->user_id
        ]);

        return back()->with('success', 'Device registered successfully');
    }

    public function deleteDevice(Device $device)
    {
        $device->delete();
        return back()->with('success', 'Device deleted successfully');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|string', // 'all' or comma-separated user IDs
        ]);

        try {
            if ($request->recipients === 'all') {
                // Send to all non-admin users
                $users = User::where('is_admin', false)->get();
                foreach ($users as $user) {
                    Notification::create([
                        'title' => $request->title,
                        'message' => $request->message,
                        'sender_id' => auth()->id(),
                        'recipient_id' => $user->id,
                        'is_broadcast' => true
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Broadcast notification sent successfully',
                    'recipients_count' => $users->count()
                ]);
            } else {
                // Send to specific users
                $recipientIds = explode(',', $request->recipients);
                foreach ($recipientIds as $recipientId) {
                    Notification::create([
                        'title' => $request->title,
                        'message' => $request->message,
                        'sender_id' => auth()->id(),
                        'recipient_id' => $recipientId,
                        'is_broadcast' => false
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Notifications sent successfully',
                    'recipients_count' => count($recipientIds)
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getNotifications()
    {
        $notifications = Notification::with(['sender', 'recipient'])
            ->latest()
            ->take(50)
            ->get();

        return response()->json($notifications);
    }

    public function getUserList()
    {
        $users = User::where('is_admin', false)
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }

    public function leaderboard()
    {
        $leaderboard = User::select('users.*')
            ->where('users.is_admin', false)
            ->leftJoin('devices', 'users.id', '=', 'devices.user_id')
            ->leftJoin('attandances', 'devices.id', '=', 'attandances.device_id')
            ->selectRaw('COUNT(DISTINCT DATE(attandances.date)) as attendance_days')
            ->selectRaw('AVG(attandances.points_earned) as average_points')
            ->groupBy('users.id')
            ->orderByDesc('average_points')
            ->orderByDesc('attendance_days')
            ->get()
            ->each(function ($user, $index) {
                $user->rank = $index + 1;
            });

        return view('admin.admin-leaderboard', compact('leaderboard'));
    }





    public function attendanceSettings()
    {
        $settings = AttandanceSetting::first();

        if ($settings) {
            // Convert string times to formatted times if they exist
            $settings->check_in_time = $settings->check_in_time ? date('H:i', strtotime($settings->check_in_time)) : '';
            $settings->check_out_time = $settings->check_out_time ? date('H:i', strtotime($settings->check_out_time)) : '';
        }

        return view('admin.attendance-settings', compact('settings'));
    }

    public function updateAttendanceSettings(Request $request)
    {
        $request->validate([
            'check_in_latitude' => 'required|numeric|between:-90,90',
            'check_in_longitude' => 'required|numeric|between:-180,180',
            'check_out_latitude' => 'required|numeric|between:-90,90',
            'check_out_longitude' => 'required|numeric|between:-180,180',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i|after:check_in_time',
        ]);

        $settings =AttandanceSetting::first() ?? new AttandanceSetting();
        $settings->fill($request->all());
        $settings->save();

        return back()->with('success', 'Attendance settings updated successfully');
    }

    public function showRegisterForm()
    {
        return view('admin.register-user');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'position' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'position' => $validated['position'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        return back()->with('success', 'User registered successfully');
    }

    public function attendanceLogs(Request $request)
    {
        // Retrieve filter inputs
        $month = $request->input('month');
        $date = $request->input('date');

        // Query to get attendance records
        $query = Attandance::with(['device.user']);

        // Apply month filter if provided
        if ($month) {
            $query->whereMonth('date', '=', date('m', strtotime($month)))
                  ->whereYear('date', '=', date('Y', strtotime($month)));
        }

         // Apply status filter if provided
        if ($status = $request->get('status')) {
            switch($status) {
                case 'on_time':
                    $query->where('status_checkin', 'On Time');
                    break;
                case 'late':
                    $query->where('status_checkin', 'Late');
                    break;
                case 'completed':
                    $query->where('status_checkout', 'Completed');
                    break;
                case 'warning':
                    $query->where('status_checkout', 'Warning: Less than 8 hours');
                    break;
            }
        }    
        // Paginate the results
        $attendances = $query->paginate(10);

        // Return the view with attendance data
        return view('admin.attendance-logs', compact('attendances'));
    }

    public function userManagement()
    {
        $users = User::with('devices')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.user-management', compact('users'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'position' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->position = $validated['position'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'User updated successfully');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully');
    }

}
