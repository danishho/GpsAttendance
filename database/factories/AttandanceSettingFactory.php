<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttandanceSetting>
 */
class AttandanceSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'check_in_latitude' => 3.857039, // Fixed for testing
            'check_in_longitude' => 103.259820, // Fixed for testing
            'check_out_latitude' => 3.857039,
            'check_out_longitude' => 103.259900,
            'radius' => 10,
            'min_hour' => 0.05,
            'max_hour' => $this->faker->numberBetween(0, 23),
            'check_in_time' => '08:00:00', // Corrected to a string
            'check_out_time' => '19:00:00', // Corrected to a string
        ];
    }
}
