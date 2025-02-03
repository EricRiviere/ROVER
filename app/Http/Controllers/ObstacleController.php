<?php

namespace App\Http\Controllers;

use App\Models\Obstacle;
use Illuminate\Http\Request;

class ObstacleController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Obstacle::all());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'map_id' => 'required|exists:maps,id',
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);

        try {
            $obstacle = Obstacle::create($request->all());
            return response()->json($obstacle, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show(Obstacle $obstacle)
    {
        try {
            return response()->json($obstacle);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Obstacle $obstacle)
    {
        $request->validate([
            'x' => 'integer',
            'y' => 'integer',
        ]);

        try {
            $obstacle->update($request->all());
            return response()->json($obstacle);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Obstacle $obstacle)
    {
        try {
            $obstacle->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}


