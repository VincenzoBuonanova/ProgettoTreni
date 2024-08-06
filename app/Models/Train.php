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
        if ($this->disruption > 10) {
            return $this->disruption . ' minutes late';
        } elseif ($this->disruption < 0) {
            return 'Early by ' . abs($this->disruption) . ' minutes';
        } else {
            return 'On time';
        }
    }
}
