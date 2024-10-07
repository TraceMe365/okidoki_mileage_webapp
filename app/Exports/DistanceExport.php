<?php

namespace App\Exports;

use App\Models\Distance;
use Maatwebsite\Excel\Concerns\FromCollection;

class DistanceExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Distance::select('booking_number', 'pickup_latitude', 'pickup_longitude', 'dropoff_latitude','dropoff_longitude','distance')->get();
    }
    
    /**
     * Define the headings for the Excel sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'BOOKING NUMBER',
            'PICKUP LATITUDE',
            'PICKUP LONGITUDE',
            'DROPOFF LATITUDE',
            'DROPOFF LONGITUDE',
            'DISTANCE'
        ];
    }
}
