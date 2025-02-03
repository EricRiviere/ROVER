<?php

namespace App\Http\Controllers;

use App\Models\Obstacle;
use Illuminate\Http\Request;

class ObstacleController extends Controller
{
    public function index()
    {
        return response()->json(Obstacle::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'map_id' => 'required|exists:maps,id',
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);

        $obstacle = Obstacle::create($request->all());
        return response()->json($obstacle, 201);
    }

    public function show(Obstacle $obstacle)
    {
        return response()->json($obstacle);
    }

    public function update(Request $request, Obstacle $obstacle)
    {
        $request->validate([
            'x' => 'integer',
            'y' => 'integer',
        ]);

        $obstacle->update($request->all());
        return response()->json($obstacle);
    }

    public function destroy(Obstacle $obstacle)
    {
        $obstacle->delete();
        return response()->json(null, 204);
    }
}

