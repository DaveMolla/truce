<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinningPattern extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'pattern_data'];

    public function images()
    {
        return $this->hasMany(PatternImage::class);
    }

}
