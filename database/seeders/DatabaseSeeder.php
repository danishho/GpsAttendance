<?php

namespace Database\Seeders;

use App\Models\AttandanceSetting;
use App\Models\Device;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Device::factory()->create([
           'device_name' => 'esp32',
            'status' => 'registered',
            'user_id' => 1,
        ]);

        AttandanceSetting::factory()->create();
    }
}
