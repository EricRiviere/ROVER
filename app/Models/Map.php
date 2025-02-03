<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'width', 'height'];

    public function obstacles()
    {
        return $this->hasMany(Obstacle::class);
    }

    public function rovers()
    {
        return $this->hasMany(Rover::class);
    }

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}

