<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteEvent extends Model
{
    use HasFactory;

    protected $table = 'athlete_event';

    protected $fillable = [
        'athlete_id', 'event_id'
    ];
}