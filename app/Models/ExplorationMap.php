<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExplorationMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'mission_id',
        'map_id',
        'exploration_data',
    ];

    protected $casts = [
        'exploration_data' => 'array',
    ];

    // Relación con la misión
    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    // Relación con el mapa
    public function map()
    {
        return $this->belongsTo(Map::class);
    }
}
