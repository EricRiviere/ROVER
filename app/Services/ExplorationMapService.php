<?php

namespace App\Services;

use App\Models\ExplorationMap;

class ExplorationMapService
{
    public function registerExplorationData($explorationMapId, $x, $y, $type)
    {
        $explorationMap = ExplorationMap::findOrFail($explorationMapId);
        $explorationData = $explorationMap->exploration_data;

        if (!is_array($explorationData)) {
            $explorationData = [];
        }

        $newPoint = ['x' => $x, 'y' => $y, 'type' => $type];

        // Evitar duplicados
        if (!in_array($newPoint, $explorationData)) {
            $explorationData[] = $newPoint;
            $explorationMap->exploration_data = $explorationData;
            $explorationMap->save();
        }

        return $explorationMap->exploration_data;
    }
}
