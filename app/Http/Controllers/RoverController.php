<?php

namespace App\Http\Controllers;

use App\Models\Rover;
use Illuminate\Http\Request;

class RoverController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Rover::all());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'x' => 'required|integer',
            'y' => 'required|integer',
            'direction' => 'required|in:N,S,E,W',
            'status' => 'required|in:inactive,deployed',
            'map_id' => 'nullable|exists:maps,id',
        ]);

        try {
            $rover = Rover::create($request->all());
            return response()->json($rover, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show(Rover $rover)
    {
        try {
            return response()->json($rover);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Rover $rover)
    {
        $request->validate([
            'name' => 'string',
            'x' => 'integer',
            'y' => 'integer',
            'direction' => 'in:N,S,E,W',
            'status' => 'in:inactive,deployed',
            'map_id' => 'nullable|exists:maps,id',
        ]);

        try {
            $rover->update($request->all());
            return response()->json($rover);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Rover $rover)
    {
        try {
            $rover->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}


