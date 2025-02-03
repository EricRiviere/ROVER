<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = ['rover_id', 'map_id', 'position', 'status', 'commands'];

    protected $casts = [
        'position' => 'array',
        'commands' => 'array',
    ];

    public function rover()
    {
        return $this->belongsTo(Rover::class);
    }

    public function map()
    {
        return $this->belongsTo(Map::class);
    }
}

