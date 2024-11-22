<?php

namespace App\Http\Controllers;


use App\Models\Attandance;
use App\Models\AttandanceSetting;
use App\Models\Device;
use Carbon\Carbon;
use PhpMqtt\Client\Facades\MQTT;

class AttandanceController extends Controller
{
    public function storeAttandance(array $data){
// Find or create the device
        $device = Device::firstOrCreate(
            ['device_name' => $data['device_name']], // Search attributes
            ['status' => 'unregistered'] // Attributes to set if not found
        );

        // Check if the device is unregistered
        if ($device->status == 'unregistered') {
            return response()->json([
                'message' => 'Device is unregistered and cannot perform attendance operations'
            ], 403); // HTTP 403 Forbidden
        }


        $receivedLat = $data['lat'];
        $receivedLon = $data['lng'];
        $receivedDateTime = Carbon::parse($data['timestamp'], 'UTC')->setTimezone('Asia/Kuala_Lumpur');


        $receivedDate = $receivedDateTime->toDateString(); // Extract date
        $receivedTime = $receivedDateTime->toTimeString(); // Extract time

        $AttendanceSetting = AttandanceSetting::first();
        $check_in_latitude = $AttendanceSetting['check_in_latitude'];
        $check_in_longitude = $AttendanceSetting['check_in_longitude'];
        $check_out_latitude = $AttendanceSetting['check_out_latitude'];
        $check_out_longitude = $AttendanceSetting['check_out_longitude'];
        $min_hour = $AttendanceSetting['min_hour'];
        $check_in_time = $AttendanceSetting['check_in_time'];
        $check_out_time = $AttendanceSetting['check_out_time'];
        $radius = $AttendanceSetting['radius'];

        // Calculate the distance from the target location
        $distance_checkin = $this->haversine($receivedLat, $receivedLon, $check_in_latitude, $check_in_longitude);
        $distance_checkout = $this->haversine($receivedLat, $receivedLon, $check_out_latitude, $check_out_longitude);

        $attendance = $this->getLastAttendance($device); // Function to get the last attendance record for the device

        // Check if the current time is past the check-out time and the user hasn't checked out
        $current_time = Carbon::now('Asia/Kuala_Lumpur')->toTimeString();
        if ($attendance && !$attendance->check_out && $current_time > $check_out_time) {
            $this->updateCheckOut($attendance, $check_out_time);
        }

        if ($distance_checkin <= $radius+10) {

//            create a condition for !attandence and not equal to unreqistered
            if(!$attendance || $attendance->date !== $receivedDate && $device->status == 'unregistered'){

                if ($receivedTime >= $check_in_time) {
                    // Calculate the difference in minutes between the scheduled check-in time and the actual check-in time
                    $checkInTimeScheduled = Carbon::parse($check_in_time, 'Asia/Kuala_Lumpur');
                    $diffInMinutes = $checkInTimeScheduled->diffInMinutes($receivedDateTime);

                    // Determine the status and points_earned
                    $statusAndPoints = $this->determineStatusAndPoints($diffInMinutes);

                    // Send MQTT message to turn on the buzzer for check-in
                    MQTT::publish('buzzer', 'on');
                    return $this->CreateAttandance($device, $receivedDate, $receivedTime, $receivedDateTime,$statusAndPoints);
                }
            }



            date_default_timezone_set('Asia/Kuala_Lumpur');
            $checkInTime = strtotime($attendance['check_in']);
            $minimumHours = $min_hour * 3600;

            if ($attendance->check_out && $receivedTime < $check_out_time) {
                // If there's a previous check-out, calculate the interval between the last check-out and the current check-in
                $checkOutTime = strtotime($attendance['check_out']);
                $currentTime = strtotime($receivedTime);
                $interval = $currentTime - $checkOutTime;
                $attendance->update([
                    'interval_time' => $attendance->interval_time + $interval,
                    'check_out' => null
                ]);
                // Send MQTT message to turn on the buzzer for check-in
                MQTT::publish('buzzer', 'on');
                return response()->json(['message' => 'Checked in again after interval', 'interval' => $interval]);
            }
//            else {
//                if ($receivedTime >= $check_out_time) {
//
//                    $this->updateCheckOut($attendance,$receivedTime);
//                } else {
//                    return response()->json(['message' => "not enter minumun hour"]);
//                }
//            }
        // Save attendance logic here
            return response()->json(['message' => "inside the radius, distance:{$distance_checkin}"]);
        }elseif ($distance_checkout <= $radius+10 ) {
            if ($attendance && $attendance->date == $receivedDate && $attendance->check_out == null) {
                // Send MQTT message to turn on the buzzer for check-in
                MQTT::publish('buzzer', 'on');
               return $this->updateCheckOut($attendance, $receivedTime);
            }else {
                return response()->json(['message' => "no attandance recorded"]);
            }

        } else {

            return response()->json([
                'message' => "OUTSIDE RADIUS, distance:{$distance_checkin}"
            ]);

        }



    }


    // Haversine formula to calculate distance in meters
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius of the Earth in meters

        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Differences
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        // Haversine formula
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in meters
    }

    private function getLastAttendance($device)
    {
        // Fetch the last attendance record for the device
        $lastAttendance = Attandance::where('device_id', $device->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $lastAttendance;
    }

    private function CreateAttandance($device,$receivedDate, $receivedTime, $receivedDateTime, $statusAndPoints)
    {
        try{
            Attandance::create([
                'device_id' => $device->id,
                'check_in' => $receivedTime,
                'date' => $receivedDate,
                'interval_time' => 0,
                'status_checkin' => $statusAndPoints['status'], // Correct assignment
                'points_earned' => $statusAndPoints['points_earned'], // Add points_earned

            ]);

            // If successful, return a success response
            return response()->json([
                'message' => "Attendance record created successfully for {$receivedDateTime}",
            ]);

        }catch (\Exception $e){
            return response()->json([
                'error' => 'Failed to create attendance record',
                'details' => $e->getMessage(),
            ], 500); // HTTP 500 Internal Server Error
        }


    }

    private function updateCheckOut($attendance, $receivedTime)
    {
        // Calculate the total hours based on check_in and receivedTime
        $checkInTime = $attendance->check_in;
        $checkOutTime = $receivedTime;

        // Convert times to Carbon instances for easy calculation
        $checkIn = \Carbon\Carbon::parse($checkInTime);
        $checkOut = \Carbon\Carbon::parse($checkOutTime);

        // Calculate the difference in hours
//        $totalHours = $checkIn->diffInHours($checkOut);
//        $totalHours = $totalHours- $attendance->interval_time;
        $totalHours = max(0, $checkIn->diffInHours($checkOut) - $attendance->interval_time);


        $attendance->update([
            'check_out' => $receivedTime,
            'total_hours' => $totalHours,
            'status_checkout' => $totalHours < 8 ? 'Warning: Less than 8 hours' : 'Completed'
        ]);

        return response()->json([
            'message' => 'updated checkout',
        ]);

    }

    private function determineStatusAndPoints($diffInMinutes)
    {
        if ($diffInMinutes <= 90) {
            return ['status' => 'On Time', 'points_earned' => 10];
        } else {
            return ['status' => 'Late', 'points_earned' => 0];
        }

        // if ($diffInMinutes <= 5) {
        //     return ['status' => 'On Time', 'points_earned' => 10];
        // } elseif ($diffInMinutes <= 15) {
        //     return ['status' => 'Slightly Late', 'points_earned' => 5];
        // } elseif ($diffInMinutes <= 30) {
        //     return ['status' => 'Late', 'points_earned' => 2];
        // } else {
        //     return ['status' => 'Very Late', 'points_earned' => 0];
        // }
    }


}
