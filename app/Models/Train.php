<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    protected $table = 'trains';

    protected $fillable = [
        'train_number',
        'departure_station_description',
        'departure_date',
        'arrival_station_description',
        'arrival_date',
        'delay_amount',
        'saved_at_date',
        'saved_at_time'
    ];
}
