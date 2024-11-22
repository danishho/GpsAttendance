<?php

namespace Database\Seeders;

use App\Models\Attandance;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttandanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('attandances')->insert([
             [
                 'check_in' => '08:23:37', // 9 AM check-in time
                 'check_out' => null, // No check-out time initially
                 'interval_time' => 0, // Initial interval time
                 'date' => Carbon::now('Asia/Kuala_Lumpur')->toDateString(), // Today's date in Malaysia timezone
                 'status_checkin' => 'On Time', // Status is On Time
                 'total_hours' => 0, // Total hours initially 0
                 'points_earned' => 10, // Points earned is 10
                 'device_id' => 1// Device ID
             ]
         ]);

        DB::table('attandances')
        ->where('device_id', 1)
        ->update([
            'check_out' => '17:47:03',
            'interval_time' => 480, // 8 hours in minutes
            'status_checkout' => 'Completed',
            'total_hours' => 9.24,
            // Add any other fields you want to update
        ]);
    }
}
