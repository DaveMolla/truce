<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatternImage extends Model
{
    use HasFactory;

    public function winningPattern()
    {
        return $this->belongsTo(WinningPattern::class);
    }

}
