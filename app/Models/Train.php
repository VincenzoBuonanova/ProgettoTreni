<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_number',
        'departure_station',
        'departure_time',
        'arrival_station',
        'arrival_time',
        'disruption',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];


    public function getDisruptionStatusAttribute()
    {
        $disruption = (int) $this->disruption; // Forza la conversione a numero intero

        if ($disruption > 10) {
            return $disruption . ' minutes late';
        } elseif ($disruption < 0) {
            return 'Early by ' . abs($disruption) . ' minutes';
        } else {
            return 'On time';
        }
    }
}
