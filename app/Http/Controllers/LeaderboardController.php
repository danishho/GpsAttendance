<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AttendanceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::select('users.name', 'users.id')
            ->addSelect(DB::raw('COALESCE(AVG(attandances.points_earned), 0) as average_points'))
            ->addSelect(DB::raw('COUNT(DISTINCT DATE(attandances.date)) as attendance_days'))
            ->leftJoin('devices', 'users.id', '=', 'devices.user_id') // Join with devices
            ->leftJoin('attandances', 'devices.id', '=', 'attandances.device_id') // Join with attandances via devices
            ->where('users.is_admin', false)
            ->groupBy('users.id', 'users.name');



        // Apply search filter if search term exists
        if ($search) {
            $query->where('users.name', 'LIKE', '%' . $search . '%');
        }

        $leaderboard = $query->orderByDesc('average_points')
            ->orderByDesc('attendance_days')
            ->get()
            ->map(function ($user, $index) {
                $user->rank = $index + 1;
                return $user;
            });

        // Get top performers for quick stats (excluding admins)
        $topPerformer = $leaderboard->first();
        $totalParticipants = $leaderboard->count();
        $averageAttendance = $leaderboard->avg('attendance_days');

        // Get current user's rank for personalized display (only if not admin)
        $currentUserRank = !auth()->user()->is_admin ?
            $leaderboard->where('id', Auth::id())->first()?->rank ?? '-' :
            null;

        return view('leaderboard', [
            'leaderboard' => $leaderboard,
            'currentStatus' => session('currentStatus', 'Outside Geofence'),
            'search' => $search,
            'topPerformer' => $topPerformer,
            'totalParticipants' => $totalParticipants,
            'averageAttendance' => round($averageAttendance, 1),
            'currentUserRank' => $currentUserRank,
            'isAuthenticated' => Auth::check(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
