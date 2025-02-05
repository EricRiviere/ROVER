<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = ['rover_id', 'map_id', 'x', 'y', 'movements', 'mission_status', 'exploration_map_id',];

    protected $casts = [
        'movements' => 'array',
    ];

    protected $attributes = [
        'mission_status' => 'active',
    ];

    public function rover()
    {
        return $this->belongsTo(Rover::class);
    }

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    public function explorationMap()
    {
        return $this->belongsTo(ExplorationMap::class, 'exploration_map_id');
    }
}


