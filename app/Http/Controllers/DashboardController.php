<?php

namespace App\Http\Controllers;

use App\Models\Attandance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Redirect admins to admin dashboard
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // Fetch attendances for all devices registered to the user
        $attendanceRecords = Attandance::whereHas('device', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->get();

        // Calculate attendance statistics
        $totalCheckins = $attendanceRecords->count();
        $totalCheckouts = $attendanceRecords->whereNotNull('check_out')->count();
        $monthlyAttendance = $attendanceRecords
            ->where('date', '>=', now()->startOfMonth())
            ->count();
        $averagePoints = $attendanceRecords->avg('points_earned');

        // Get today's latest attendance record for the user
        $todayRecord = Attandance::whereHas('device', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('date', now()->toDateString())
            ->latest()
            ->first();

        // Determine current status
        $currentStatus = $todayRecord && $todayRecord->check_out
            ? 'Inside Geofence'
            : 'Outside Geofence';



        return view('dashboard', compact(
            'attendanceRecords',
            'totalCheckins',
            'totalCheckouts',
            'monthlyAttendance',
            'averagePoints',
            'currentStatus'
        ));
    }
}
