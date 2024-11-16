<?php

namespace App\Console\Commands;

use App\Http\Controllers\AttandanceController;
use App\Models\Attandance;
use App\Models\AttandanceSetting;
use App\Models\Device;
use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use App\Models\GpsData;
class Esp32 extends Command
{
    protected $signature = 'mqtt:subscribe';
    protected $description = 'Subscribe to MQTT topics and save GPS data to the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $mqtt = MQTT::connection();
        $AttandanceController = new AttandanceController();

        $mqtt->subscribe('coordinate', function (string $topic, string $message) use ($AttandanceController) {
            $this->info("Received message on topic [{$topic}]: {$message}");

            $data = json_decode($message, true);

            if ($data) {
                $response =  $AttandanceController->storeAttandance($data);
                $this->info($response);
            } else {
                $this->error("Failed to decode JSON message.");
            }
        });

        $AttendanceSetting = AttandanceSetting::first();
        $check_out_time = $AttendanceSetting['check_out_time'];

        $attendances=Attandance::all();
        $currentTime = time();


        foreach ($attendances as $attendance) {
            // Update check-out if the current time has passed the check-out time and check-out is still null
            if ($currentTime >= $check_out_time && $attendance->check_out == null) {
                $attendance->update(['check_out' => $check_out_time]); // Format as needed
                $this->info("Check-out time updated for attendance ID: {$attendance->id}");
            }
        }

        $mqtt->loop(true);

        return 0;
    }
}
