<?php

namespace App\Http\Controllers;

use App\Exports\ArrayExport;
use App\Imports\MoviesImport;
use App\Imports\VehicleImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
class ExcelImportController extends Controller
{
    protected  $wialonController;
    public function __construct(WialonController $wialonController)
    {
        set_time_limit(300);
        $this->wialonController = $wialonController;
    }
    public function showUploadForm()
    {
        return view('upload');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $fileNameWithExtension = $file->getClientOriginalName();
        $array = Excel::toArray(new VehicleImport, $file);
        $newArray = [];
        foreach ($array[0] as $firstIndex => $record) {
            if ($firstIndex > 0) {
                // $newArray[$firstIndex] = $newArray[$firstIndex] ?? ['unit' => null, 'in' => null, 'out' => null];
                foreach ($record as $col => $row) {
                    if ($col == 0 && !empty($row)) {
                        $newArray[$firstIndex]['booking'] = $row;
                    } 
                    else if ($col == 1 && !empty($row)) {
                        $newArray[$firstIndex]['unit'] = $row;
                    }
                    else if ($col == 2 && !empty($row)) {
                        // print_r($row." , ".$this->getTimestamp($row));die();
                        $newArray[$firstIndex]['in'] = $this->getTimestamp($row);
                    }
                    else if ($col == 3 && !empty($row)) {
                        $newArray[$firstIndex]['out'] = $this->getTimestamp($row);
                    }
                }
            }
        }

        $this->wialonController->getSessionEID();
        $units = $this->wialonController->getUnits();
        foreach($newArray as &$element){
            foreach($units['items'] as $unit){
                if($this->stringsEqual($unit["nm"],$element['unit'])){
                    $element['id'] = $unit['id'];
                }
            }
        }

        $this->wialonController->getSessionEID();
        $this->wialonController->setTimeZone();
        foreach($newArray as $key=>&$vehicle){
            $this->wialonController->executeReport($vehicle['id'],$vehicle['in'],$vehicle['out']);
            $response = $this->wialonController->getRecords();
            $vehicle['distance'] = $response[0]['c'][0];
        }

        foreach($array[0] as $column => &$row){
            if($column==0){
                $row[]='DISTANCE';
            }
            if($column<=count($newArray) && $column!=0){
                $row[]= $newArray[$column]['distance'];
            }
        }
        $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
        return Excel::download(new ArrayExport($array), $fileName.'_distance.xlsx');
    }

    public function getTimestamp($excelDate)
    {
        $excelBaseDate = new \DateTime('1899-12-30 00:00:00', new \DateTimeZone('Asia/Kolkata'));
        $secondsSinceExcelEpoch = $excelDate * 86400;
        $excelBaseTimestamp = $excelBaseDate->getTimestamp();
        $finalUnixTimestamp = $excelBaseTimestamp + $secondsSinceExcelEpoch;
        return $finalUnixTimestamp;
    }

    function stringsEqual($str1, $str2) {
        $str1 = preg_replace('/\s+/', '', $str1);
        $str2 = preg_replace('/\s+/', '', $str2);
        $str1 = strtolower($str1);
        $str2 = strtolower($str2);
        return strpos($str1, $str2) !== false || strpos($str2, $str1) !== false;
    }

    function unixToExcelDate($timestamp) {
        $secondsInADay = 86400;
        $excelDate = 25569 + ($timestamp / $secondsInADay);
        return $excelDate;
    }
    
    function formatExcelDate($excelDate) {
        $unixTimestamp = ($excelDate - 25569) * 86400;
        $date = (new \DateTime())->setTimestamp($unixTimestamp);
        return $date->format('Y-m-d H:i \a');
    }
}