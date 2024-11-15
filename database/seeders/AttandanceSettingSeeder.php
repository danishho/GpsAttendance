<?php

namespace Database\Seeders;

use App\Models\AttandanceSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttandanceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AttandanceSetting::factory()->create([
            'check_in_latitude' => 3.857039,
            'check_in_longitude' => 103.259820,
            'check_out_latitude' => 3.857039,
            'check_out_longitude' => 103.259900,
            'radius' => 10,
            'min_hour' => 0.05,
            'max_hour' => 0.08,
        ]);
    }
}
