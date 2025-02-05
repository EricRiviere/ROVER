<?php

namespace App\Http\Controllers;

use App\Models\ExplorationMap;
use App\Models\Map;
use App\Models\Mission;
use Illuminate\Http\Request;

class ExplorationMapsController extends Controller
{
    // Inicializa un mapa de exploración para una misión
    public function createForMission($missionId)
    {
        $mission = Mission::findOrFail($missionId);
        $map = Map::findOrFail($mission->map_id);

        $explorationMap = ExplorationMap::create([
            'mission_id' => $mission->id,
            'map_id' => $map->id,
            'exploration_data' => json_encode([]), // Inicializar vacío
        ]);

        return response()->json($explorationMap, 201);
    }

    // Obtener todos los mapas de exploración
    public function index()
    {
        return response()->json(ExplorationMap::all(), 200);
    }

    // Obtener un solo mapa de exploración por ID
    public function show($id)
    {
        return response()->json(ExplorationMap::findOrFail($id), 200);
    }

    // Actualizar un mapa de exploración (PUT/PATCH)
    public function update(Request $request, $id)
    {
        $explorationMap = ExplorationMap::findOrFail($id);
        $explorationMap->update($request->all());

        return response()->json(['message' => 'Exploration map updated', 'data' => $explorationMap], 200);
    }

    // Eliminar un mapa de exploración
    public function destroy($id)
    {
        $explorationMap = ExplorationMap::findOrFail($id);
        $explorationMap->delete();

        return response()->json(['message' => 'Exploration map deleted'], 200);
    }

    // Registrar exploración u obstáculo (unificada en una sola función)
    public function registerExplorationData(Request $request, $explorationMapId)
    {
        $request->validate([
            'x' => 'required|integer',
            'y' => 'required|integer',
            'type' => 'required|string|in:explored,obstacle',
        ]);

        $explorationMap = ExplorationMap::findOrFail($explorationMapId);
        $explorationData = $explorationMap->exploration_data;
        if (!is_array($explorationData)) {
            $explorationData = [];
        }


        $newPoint = ['x' => $request->x, 'y' => $request->y, 'type' => $request->type];

        // Evitar duplicados
        if (!in_array($newPoint, $explorationData)) {
            $explorationData[] = $newPoint;
            $explorationMap->exploration_data = $explorationData;
            $explorationMap->save();
        }

        return response()->json([
            'message' => 'Exploration data recorded',
            'data' => $explorationMap->exploration_data
        ], 200);
    }

    // Obtener toda la información de exploración (explorados + obstáculos)
    public function getExplorationData($explorationMapId)
    {
        $explorationMap = ExplorationMap::findOrFail($explorationMapId);
        return response()->json($explorationMap->exploration_data, 200);
    }
}
