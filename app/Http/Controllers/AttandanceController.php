<?php

namespace App\Http\Controllers;


use App\Models\Attandance;
use App\Models\AttandanceSetting;
use App\Models\Device;
use App\Models\GpsData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttandanceController extends Controller
{
    public function storeAttandance(array $data){
// Find or create the device
        $device = Device::firstOrCreate(
            ['device_name' => $data['device_name']], // Search attributes
            ['user_id' => 1, 'status' => 'unregistered'] // Attributes to set if not found
        );


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
        $max_hour = $AttendanceSetting['max_hour'];
        $check_in_time = $AttendanceSetting['check_in_time'];
        $check_out_time = $AttendanceSetting['check_out_time'];
        $radius = $AttendanceSetting['radius'];

        // Calculate the distance from the target location
        $distance = $this->haversine($receivedLat, $receivedLon, $check_in_latitude, $check_in_longitude);




        if ($distance <= $radius+5) {
            $attendance = $this->getLastAttendance($device); // Function to get the last attendance record for the device
//            create a condition for !attandence and not equal to unreqistered
            if(!$attendance || $attendance->Date !== $receivedDate){
                return $this->CreateAttandance($device, $receivedDate, $receivedTime,$receivedDateTime);
            }
            date_default_timezone_set('Asia/Kuala_Lumpur');
            // If already checked in, calculate time difference for check-out
            $checkInTime = strtotime($attendance['Check_in']);
            $currentTime = time();
            $minimumHours = $min_hour * 3600;

            if (($currentTime - $checkInTime) >= $minimumHours) {
                $this->updateCheckOut($attendance);
            } else {
                return response()->json(['message' => "not enter minumun hour"]);
            }



            // Save attendance logic here
            return response()->json(['message' => "inside the radius, distance:{$distance}"]);
        } else {
            return response()->json([
                'message' => "OUTSIDE RADIUS, distance:{$distance}"
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

    private function CreateAttandance($device,$receivedDate, $receivedTime, $receivedDateTime)
    {

        Attandance::create([
            'device_id' => $device->id,
            'check_in' => $receivedTime,
            'Date' => $receivedDate,
            'status' => 'present', // Adjust the status as needed
        ]);

        return response()->json([
            'message' => "Attendance record created, {$receivedDateTime}",
        ]);
    }

    private function updateCheckOut($attendance)
    {
        $checkOutTime = date('H:i:s');
        $attendance->update([
          'check_out' => $checkOutTime,
          'status' => 'completed',
        ]);

        return response()->json([
            'message' => 'updated checkout',
        ]);

    }


}
