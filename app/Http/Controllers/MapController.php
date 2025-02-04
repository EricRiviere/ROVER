<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\Obstacle;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Map::all());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'width' => 'required|integer|min:200|max:200',
            'height' => 'required|integer|min:200|max:200',
        ]);

        try {
            // Crear el mapa
            $map = Map::create($request->all());

            // Crear 50 obstáculos con coordenadas aleatorias dentro del mapa
            $createdObstacles = 0;
            while ($createdObstacles < 50) {
                // Generar coordenadas aleatorias
                $x = rand(0, $map->width);
                $y = rand(0, $map->height);

                // Verificar si ya existe un obstáculo en esa coordenada
                $existingObstacle = Obstacle::where('map_id', $map->id)
                                            ->where('x', $x)
                                            ->where('y', $y)
                                            ->first();

                // Si no existe un obstáculo, crear uno
                if (!$existingObstacle) {
                    $map->obstacles()->create([
                        'x' => $x,
                        'y' => $y,
                    ]);
                    $createdObstacles++;
                }
            }

            return response()->json($map->load('obstacles'), 201); // Incluir los obstáculos en la respuesta
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show(Map $map)
    {
        try {
            return response()->json($map->load('obstacles')); // Cargar obstáculos relacionados
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, Map $map)
    {
        $request->validate([
            'name' => 'string',
            'width' => 'integer|min:200 | max:200',
            'height' => 'integer|min:200| max:200',
        ]);

        try {
            $map->update($request->all());
            return response()->json($map);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Map $map)
    {
        try {
            $map->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}



