<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','super_agent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function superAgent()
    {
        return $this->belongsTo(SuperAgent::class, 'super_agent_id');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
