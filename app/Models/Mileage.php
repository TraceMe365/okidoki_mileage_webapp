<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mileage extends Model
{
    use HasFactory;
    protected $table = "mileage";
    protected $fillable = [
        'booking_id',
        'vehicle_id',
        'vehicle_name',
        'from_time',
        'to_time',
        'mileage',
    ];
}
