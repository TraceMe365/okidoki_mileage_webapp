<?php

namespace App\Http\Controllers;

use App\Exports\DistanceExport;
use App\Exports\DistanceMultipleExport;
use App\Exports\MileageExport;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends Controller
{
    public function downloadMileage(){
        return Excel::download(new MileageExport(), 'Mileage_' . date('U') . '.xlsx');
    }

    public function downloadDistance(){
        return Excel::download(new DistanceExport(), 'Distance_' . date('U') . '.xlsx');
    }

    public function downloadDistanceMultiple(){
        return Excel::download(new DistanceMultipleExport(), 'Multiple_Distance_'.date('U').'.xlsx');
    }
}
