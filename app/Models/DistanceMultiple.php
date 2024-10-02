<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistanceMultiple extends Model
{
    use HasFactory;

    protected $table = 'multiple_distance';

    protected $fillable = [
        'booking_id',
        'pickup_latitude',
        'pickup_longitude',
        'via_locations',
        'delivery_latitude',
        'delivery_longitude',
        'distance',
        'time',
    ];

}
