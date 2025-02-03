<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        return response()->json(Map::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'width' => 'required|integer|min:1',
            'height' => 'required|integer|min:1',
        ]);

        $map = Map::create($request->all());
        return response()->json($map, 201);
    }

    public function show(Map $map)
    {
        return response()->json($map);
    }

    public function update(Request $request, Map $map)
    {
        $request->validate([
            'name' => 'string',
            'width' => 'integer|min:1',
            'height' => 'integer|min:1',
        ]);

        $map->update($request->all());
        return response()->json($map);
    }

    public function destroy(Map $map)
    {
        $map->delete();
        return response()->json(null, 204);
    }
}

