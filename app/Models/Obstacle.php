<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obstacle extends Model
{
    use HasFactory;

    protected $fillable = ['map_id', 'x', 'y'];

    public function map()
    {
        return $this->belongsTo(Map::class);
    }
}

