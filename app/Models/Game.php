<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'bet_amount',
        'total_players',
        'total_calls',
        'status',
        'total_bet_amount',
        'profit',
    ];
}
