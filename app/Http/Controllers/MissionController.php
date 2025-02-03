<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index()
    {
        return response()->json(Mission::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'rover_id' => 'required|exists:rovers,id',
            'map_id' => 'required|exists:maps,id',
            'position' => 'required|array',
            'position.x' => 'required|integer',
            'position.y' => 'required|integer',
            'status' => 'required|in:in_progress,completed',
            'commands' => 'nullable|array',
        ]);

        // $mission = Mission::create($request->all());
        // return response()->json($mission, 201);
        try {
            $mission = Mission::create($request->all());
            return response()->json($mission, 201); // Responde con el objeto misión y el código de estado 201 (creado)
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400); // Devuelve el mensaje de error si algo falla
        }
    }

    public function show(Mission $mission)
    {
        return response()->json($mission);
    }

    public function update(Request $request, Mission $mission)
    {
        $request->validate([
            'position' => 'array',
            'position.x' => 'integer',
            'position.y' => 'integer',
            'status' => 'in:in_progress,completed',
            'commands' => 'array',
        ]);

        $mission->update($request->all());
        return response()->json($mission);
    }

    public function destroy(Mission $mission)
    {
        $mission->delete();
        return response()->json(null, 204);
    }
}

