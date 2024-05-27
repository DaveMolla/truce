<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_user_id',
        'bet_amount',
        'total_players',
        'total_calls',
        'status',
        'total_bet_amount',
        'profit',
    ];

    public function branchUser()
    {
        return $this->belongsTo(User::class, 'branch_user_id');
    }

    public function bingoCards()
    {
        return $this->belongsToMany(BingoCard::class, 'game_bingo_card', 'game_id', 'bingo_card_id');
    }
}
