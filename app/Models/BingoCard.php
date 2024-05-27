<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoCard extends Model
{
    use HasFactory;
    protected $fillable = ['card_data'];

    protected $casts = [
        'card_data' => 'array',
    ];
}
