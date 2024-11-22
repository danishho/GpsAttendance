<?php

namespace App\Http\Controllers;

use App\Models\AttandanceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceSettingsController extends Controller
{
    public function reset()
    {
        try {
            $settings = AttandanceSetting::first();

            if ($settings) {
                $settings->update([
                    'check_in_latitude' => null,
                    'check_in_longitude' => null,
                    'check_out_latitude' => null,
                    'check_out_longitude' => null,
                    'check_in_time' => null,
                    'check_out_time' => null,
                    'radius' => 50
                ]);
            } else {
                AttandanceSetting::create([
                    'radius' => 50
                ]);
            }

            return redirect()->back()->with('success', 'Settings reset successfully');
        } catch (\Exception $e) {
            \Log::error('Reset settings error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reset settings');
        }
    }
}
