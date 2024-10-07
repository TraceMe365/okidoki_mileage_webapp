<?php

namespace App\Exports;

use App\Models\Mileage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MileageExport implements FromCollection, WithHeadings
{
    /**
     * Return the data to be exported as a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Mileage::select('booking_id', 'vehicle_id', 'vehicle_name', 'from_time','to_time','mileage')->get();
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
            'VEHICLE ID',
            'VEHICLE NUMBER',
            'FROM',
            'TO',
            'MILEAGE'
        ];
    }
}
