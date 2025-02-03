<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rover extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'x', 'y', 'direction', 'status', 'map_id'];

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}

