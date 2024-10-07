<?php

namespace App\Exports;

use App\Models\DistanceMultiple;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DistanceMultipleExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DistanceMultiple::select(
            'booking_id',
            'pickup_latitude',
            'pickup_longitude',
            'via_locations',
            'delivery_latitude',
            'delivery_longitude',
            'distance',
            'time'
        )->get();

        if ($data->isEmpty()) {
            return new Collection([]);
        }

        return $data;
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
            'VIA LOCATIONS',
            'DROPOFF LATITUDE',
            'DROPOFF LONGITUDE',
            'DISTANCE',
            'TIME'
        ];
    }
}
