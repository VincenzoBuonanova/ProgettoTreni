<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Train extends Model
{
    use HasFactory;

    protected $table = 'trains';

    // Campi che possono essere riempiti in massa
    protected $fillable = [
        'TrainNumber',
        'DepartureStationDescription',
        'DepartureDate',
        'ArrivalStationDescription',
        'ArrivalDate',
        'DelayAmount'
    ];

    // Cast dei campi datetime
    protected $casts = [
        'DepartureDate' => 'datetime',
        'ArrivalDate' => 'datetime',
        'saved_at' => 'datetime',
    ];
}
