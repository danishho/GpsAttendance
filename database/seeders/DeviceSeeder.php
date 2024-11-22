<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create registered devices (assigned to random non-admin users)
        $users = User::where('is_admin', false)->get();

        if ($users->count() > 0) {
            Device::create([
                'device_name' => 'GL1',
                'user_id' => 2,
                'status' => 'registered'
            ]);


        }
    }
}
