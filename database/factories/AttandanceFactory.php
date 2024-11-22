<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attandance>
 */
class AttandanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            [
                'check_in' => '09:00:00', // 9 AM check-in time
                'check_out' => null, // No check-out time initially
                'interval_time' => 0, // Initial interval time
                'date' => Carbon::now('Asia/Kuala_Lumpur')->toDateString(), // Today's date in Malaysia timezone
                'status' => 'On Time', // Status is On Time
                'total_hours' => 0, // Total hours initially 0
                'points_earned' => 10, // Points earned is 10
                'device_id' => 3 // Device ID
            ],
            [
                'check_in' => '10:00:00', // 10 AM check-in time
                'check_out' => '18:00:00', // 6 PM check-out time
                'interval_time' => 8, // Interval time (hours between check-in and check-out)
                'date' => '2024-11-18', // Today's date in Malaysia timezone
                'status' => 'On Time', // Status is On Time
                'total_hours' => 8, // Total hours between check-in and check-out
                'points_earned' => 10, // Points earned is 10
                'device_id' => 3 // Device ID
            ]
        ];

    }
}
