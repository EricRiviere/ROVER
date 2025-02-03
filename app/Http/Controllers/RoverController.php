<?php

namespace App\Http\Controllers;

use App\Models\Rover;
use Illuminate\Http\Request;

class RoverController extends Controller
{
    public function index()
    {
        return response()->json(Rover::all());
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

        $rover = Rover::create($request->all());
        return response()->json($rover, 201);
    }

    public function show(Rover $rover)
    {
        return response()->json($rover);
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

        $rover->update($request->all());
        return response()->json($rover);
    }

    public function destroy(Rover $rover)
    {
        $rover->delete();
        return response()->json(null, 204);
    }
}

