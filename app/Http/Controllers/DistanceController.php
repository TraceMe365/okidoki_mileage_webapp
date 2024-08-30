<?php

namespace App\Http\Controllers;

use App\Imports\VehicleImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Distance;

class DistanceController extends Controller
{
    protected $google_maps_token = "AIzaSyC0Nh39yAtFIg-x83gBbRCIfOOE_N8Qdl0";

    public function __construct()
    {
        set_time_limit(0);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        $array = Excel::toArray(new VehicleImport, $file);
        $newArray = [];
        $booking_numbers = Distance::select("booking_number")->get()->toArray();
        $booking_ids = array_column($booking_numbers,"booking_number");
        foreach ($array[0] as $firstIndex => $record) {
            if($firstIndex>0){
                if(isset($record[0]) && !in_array($record[0], $booking_ids)){
                    $pLat = $record[1];
                    $pLon = $record[2];
                    $dLat = $record[3];
                    $dLon = $record[4];
                    
                    $distance = $this->getDistanceFromGoogle($pLat,$pLon,$dLat,$dLon);
                    if(isset($distance['rows'][0]['elements'][0]['distance']['text'])){
                        $distance = $distance['rows'][0]['elements'][0]['distance']['text'];
                    }
                    else{
                        $distance = 'N/A';
                    }

                    // $newArray[$firstIndex]['booking']    = $record[0];
                    // $newArray[$firstIndex]['pickup_lat'] = $pLat;
                    // $newArray[$firstIndex]['pickup_lon'] = $pLon;
                    // $newArray[$firstIndex]['drop_lat']   = $dLat;
                    // $newArray[$firstIndex]['drop_lon']   = $dLon;
                    // $newArray[$firstIndex]['distance']   = $distance;
                    
                    Distance::create([
                        'booking_number'    => $record[0],
                        'pickup_latitude'   => $pLat,
                        'pickup_longitude'  => $pLon,
                        'dropoff_latitude'  => $dLat,
                        'dropoff_longitude' => $dLon,
                        'distance'          => $distance,
                    ]);
                    
                }
            }
        }
    }

    function getDistanceFromGoogle($fromLat,$fromLon,$toLat,$toLon)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://maps.googleapis.com/maps/api/distancematrix/json?destinations='.$toLat.','.$toLon.'&origins='.$fromLat.','.$fromLon.'&units=metric&avoid=highways&key='.$this->google_maps_token,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response,true);
    }

}
