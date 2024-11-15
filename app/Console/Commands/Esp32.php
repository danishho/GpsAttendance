<?php

namespace App\Console\Commands;

use App\Http\Controllers\AttandanceController;
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

        $mqtt->loop(true);

        return 0;
    }
}
