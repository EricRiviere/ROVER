<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mission;
use App\Models\Rover;
use App\Models\Map;

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
            'x' => 'required|integer|min:100|max:200',
            'y' => 'required|integer|min:100|max:200'
        ]);

        $mission = Mission::create([
            'rover_id' => $request->rover_id,
            'map_id' => $request->map_id,
            'x' => $request->x,
            'y' => $request->y,
            'movements' => json_encode([])
        ]);

        return response()->json($mission, 201);
    }

    public function show($id)
    {
        return response()->json(Mission::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $mission = Mission::findOrFail($id);

        $request->validate([
            'x' => 'integer|min:100|max:200',
            'y' => 'integer|min:100|max:200',
            'movements' => 'array'
        ]);

        $mission->update($request->only(['x', 'y', 'movements']));

        return response()->json($mission);
    }

    public function destroy($id)
    {
        $mission = Mission::findOrFail($id);
        $mission->delete();
        return response()->json(null, 204);
    }

    public function move(Request $request, $id)
    {
        $mission = Mission::findOrFail($id);
        $rover = Rover::findOrFail($mission->rover_id);
        $map = Map::findOrFail($mission->map_id);

        $request->validate([
            'commands' => 'required|string'
        ]);

        $commands = str_split($request->commands);
        $validCommands = ['F', 'L', 'R'];
        
        foreach ($commands as $command) {
            if (!in_array($command, $validCommands)) {
                return response()->json(['error' => 'Comando inválido'], 400);
            }
        }

        $direction = $rover->direction;
        $x = $mission->x;
        $y = $mission->y;
        $executedCommands = [];

        foreach ($commands as $command) {
            if ($command === 'L') {
                $direction = $this->turnLeft($direction);
            } elseif ($command === 'R') {
                $direction = $this->turnRight($direction);
            } elseif ($command === 'F') {
                [$newX, $newY] = $this->calculateNewPosition($x, $y, $direction);

                if ($this->isOutOfBounds($newX, $newY) || $this->isObstacle($map, $newX, $newY)) {
                    break;
                }

                $x = $newX;
                $y = $newY;
            }

            $executedCommands[] = $command;
        }

        $mission->update([
            'x' => $x,
            'y' => $y,
            'movements' => json_encode([...json_decode($mission->movements, true), [
                'position' => ['x' => $x, 'y' => $y],
                'commands' => implode('', $executedCommands)
            ]])
        ]);

        return response()->json($mission);
    }

    private function turnLeft($direction)
    {
        return match ($direction) {
            'N' => 'W',
            'W' => 'S',
            'S' => 'E',
            'E' => 'N',
            default => $direction
        };
    }

    private function turnRight($direction)
    {
        return match ($direction) {
            'N' => 'E',
            'E' => 'S',
            'S' => 'W',
            'W' => 'N',
            default => $direction
        };
    }

    private function calculateNewPosition($x, $y, $direction)
    {
        return match ($direction) {
            'N' => [$x, $y + 1],
            'S' => [$x, $y - 1],
            'E' => [$x + 1, $y],
            'W' => [$x - 1, $y],
            default => [$x, $y]
        };
    }

    private function isOutOfBounds($x, $y)
    {
        return $x < 100 || $x > 200 || $y < 100 || $y > 200;
    }

    private function isObstacle($map, $x, $y)
    {
        return in_array(['x' => $x, 'y' => $y], json_decode($map->obstacles, true));
    }
}


