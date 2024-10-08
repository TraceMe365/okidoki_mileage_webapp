<?php

namespace App\Http\Controllers;

use App\Imports\VehicleImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Distance;
use App\Models\DistanceMultiple;
use App\Models\Mileage;

class DistanceController extends Controller
{
    protected $google_maps_token;

    public function __construct()
    {
        set_time_limit(0);
        $this->google_maps_token = env('GOOGLE_MAP_TOKEN');
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
        session()->flash('success', 'File imported successfully!');
        return redirect()->back(); 
    }

    public function importVia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        $array = Excel::toArray(new VehicleImport, $file);

        $booking_numbers = DistanceMultiple::select("booking_id")->get()->toArray();
        $booking_ids = array_column($booking_numbers,"booking_id");
        foreach ($array[0] as $firstIndex => $record) {
            if($firstIndex>0){
                if(isset($record[0]) && !in_array($record[0], $booking_ids)){
                    $pickupLat  = "";
                    $pickupLon  = "";
                    $booking    = "";
                    $deliverLat = "";
                    $deliverLon = "";
                    $via = [];
                    $arrayOfCoords = [];
                    $via_string = "";

                    foreach($record as $columnIndex => $column){
                        if($columnIndex>0){
                            if(isset($column) && $column!=''){
                                array_push($arrayOfCoords,$column);
                            }
                        }
                    }
                    
                    for($i=0;$i<count($arrayOfCoords);$i++){
                        if($i==0){
                            $pickupLat = $arrayOfCoords[$i];
                        }
                        else if($i==1){
                            $pickupLon  = $arrayOfCoords[$i];
                        }
                        else if($i>1 && $i<count($arrayOfCoords)-2){
                            array_push($via,$arrayOfCoords[$i]);
                        }
                        else if($i==count($arrayOfCoords)-2){
                            $deliverLat = $arrayOfCoords[$i];
                        }
                        else if($i==count($arrayOfCoords)-1){
                            $deliverLon = $arrayOfCoords[$i];
                        }
                    }
                    
                    foreach($via as $ind => $vi){
                        if($ind%2==0){
                            $via_string .= $vi;
                        }
                        else if($ind%2==1){
                            $via_string .= ',';
                            $via_string .= $vi;
                            $via_string .= '|';
                        }
                    }
                    
                    $response = $this->getDistanceFromGoogleMultiple($pickupLat,$pickupLon,$deliverLat,$deliverLon,$via_string);
                    if(isset($response['routes'][0]['legs'])){
                        $legs     = $response['routes'][0]['legs'];
                        $distance = 0;
                        $time     = 0;
                        foreach($legs as $leg){
                            $distance += $leg['distance']['value'];
                            $time += $leg['duration']['value'];
                        }
                        $distance = $distance/1000;
                        $roundedDistance = round($distance, 1);
                        $distanceString = (string)$roundedDistance.' km';
                        $timeInHuman = $this->secondsToTime($time);
                        DistanceMultiple::create([
                            'booking_id' => $record[0],
                            'pickup_latitude' => $pickupLat,
                            'pickup_longitude' => $pickupLon,
                            'via_locations' => $via_string,
                            'delivery_latitude' => $deliverLat,
                            'delivery_longitude' => $deliverLon,
                            'distance' => $distanceString,
                            'time' => $timeInHuman
                        ]);
                    }
                    else{
                        DistanceMultiple::create([
                            'booking_id' => $record[0],
                            'pickup_latitude' => $pickupLat,
                            'pickup_longitude' => $pickupLon,
                            'via_locations' => $via_string,
                            'delivery_latitude' => $deliverLat,
                            'delivery_longitude' => $deliverLon,
                            'distance' => 'N/A',
                            'time' => 'N/A',
                        ]);
                    }
                }
            }
        }
        session()->flash('success', 'File imported successfully!');
        return redirect()->back(); 
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

    function getDistanceFromGoogleMultiple($fromLat,$fromLon,$toLat,$toLon,$via)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://maps.googleapis.com/maps/api/directions/json?origin='.$fromLat.','.$fromLon.'&destination='.$toLat.','.$toLon.'&waypoints='.$via.'&avoid=highways&key='.$this->google_maps_token,
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

    function secondsToTime($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    function clearMileageTable(){
        Mileage::truncate();
    }

    function clearDistanceTable(){
        Distance::truncate();
    }

    function clearMultipleDistanceTable(){
        DistanceMultiple::truncate();
    }

}
