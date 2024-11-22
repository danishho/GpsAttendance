<?php

namespace Database\Seeders;

use App\Models\Attandance;
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
        // Call AdminSeeder instead of creating admin directly
        $this->call(AdminSeeder::class);


        // Create a regular user for testing
        User::create([
            'name' => 'NURFADHILAH BINTI SAPINGI',
            'email' => 'fadhilah201@gmail.com',
            'position' => 'Lecturer',
            'password' => Hash::make('user123'),
            'is_admin' => false,
        ]);


        // Create registered devices
        $user = User::where('email', 'user@geolokasi.com')->first();


        Device::create([
            'device_name' => 'GL1',
            'user_id' => 2,
            'status' => 'registered'
        ]);

        AttandanceSetting::create([
            'check_in_latitude' => 3.857039,
            'check_in_longitude' => 103.259820,
            'check_out_latitude' => 3.857039,
            'check_out_longitude' => 103.259900,
            'radius' => 50,
        ]);

//        AttandanceSetting::factory()->create();
//        $this->call(AttandanceSeeder::class);
//

    }
}
