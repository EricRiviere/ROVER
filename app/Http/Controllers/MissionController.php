<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mission;
use App\Models\Rover;
use App\Models\Map;
use App\Models\ExplorationMap;
use App\Services\ExplorationMapService;

class MissionController extends Controller
{
    protected $explorationMapService;

    public function __construct(ExplorationMapService $explorationMapService)
    {
        $this->explorationMapService = $explorationMapService;
    }
    public function index()
    {
        return response()->json(Mission::all());
    }

    public function create()
    {
        $rovers = Rover::all();
        $maps = Map::all();
        
        return view('missions.create', compact('rovers', 'maps'));
    }

    public function moveView($id)
    {
        return view('missions.move', ['missionId' => $id]);
    }

    public function showMap($id)
    {
        // Obtener la misión
        $mission = Mission::findOrFail($id);
        // Obtener el rover relacionado
        $rover = Rover::findOrFail($mission->rover_id);
        // Obtener el mapa de exploración de la misión
        $explorationMap = ExplorationMap::where('mission_id', $mission->id)->first();
    
        // Obtener los datos de exploración (casillas exploradas y obstáculos)
        if (is_array($explorationMap->exploration_data)) {
            $explorationData = $explorationMap->exploration_data;
        } else {
            $explorationData = json_decode($explorationMap->exploration_data, true);
        }
    
        // Pasar $missionId junto con las otras variables
        return view('missions.showMap', compact('mission', 'rover', 'explorationData'));
    }    

    public function store(Request $request)
    {
        $request->validate([
            'rover_id' => 'required|exists:rovers,id',
            'map_id' => 'required|exists:maps,id',
            'x' => 'required|integer|min:0|max:200',
            'y' => 'required|integer|min:0|max:200'
        ]);

        // Primero, creamos el mapa de exploración
        $explorationMap = ExplorationMap::create([
            'mission_id' => null,
            'map_id' => $request->map_id
        ]);
        
        $mission = Mission::create([
            'rover_id' => $request->rover_id,
            'map_id' => $request->map_id,
            'x' => $request->x,
            'y' => $request->y,
            'exploration_map_id' => $explorationMap->id,
            'movements' => json_encode([])
        ]);

        // Asociamos la misión al mapa de exploración
        $explorationMap->mission_id = $mission->id;
        $explorationMap->save();

        // Obtener el mapa seleccionado
        $map = Map::findOrFail($request->map_id);

        // Comprobar si hay un obstáculo en las coordenadas dadas
        if ($this->isObstacle($map, $request->x, $request->y)) {
            return response()->json([
                'error' => "No se puede crear la misión en ($request->x, $request->y), hay un obstáculo."
            ], 400);
        }

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
            'x' => 'integer|min:0|max:200',
            'y' => 'integer|min:0|max:200',
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

    public function finishMission($id)
    {
        $mission = Mission::findOrFail($id);
        
        // Marcar misión como terminada
        $mission->update(['mission_status' => 'finished']);

        return response()->json([
            'message' => 'Misión finalizada',
            'mission' => $mission
        ]);
    }
    public function move(Request $request, $id)
    {
        $mission = Mission::findOrFail($id);
        $rover = Rover::findOrFail($mission->rover_id);
        $map = Map::findOrFail($mission->map_id);
        $explorationMap = ExplorationMap::where('mission_id', $mission->id)->first(); // Obtener el mapa de exploración para la misión
    
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
        $abortMessage = null; // Inicializamos el mensaje de aborto como nulo.
    
        foreach ($commands as $command) {
            if ($command === 'L') {
                $direction = $this->turnLeft($direction);
            } elseif ($command === 'R') {
                $direction = $this->turnRight($direction);
            } elseif ($command === 'F') {
                [$newX, $newY] = $this->calculateNewPosition($x, $y, $direction);
    
                // Comprobamos si se encuentra un obstáculo o si está fuera de los límites.
                if ($this->isOutOfBounds($newX, $newY)) {
                    $abortMessage = 'Comando abortado por límite de mapa';
                    break; // Abortamos el comando si está fuera de límites.
                }
    
                if ($this->isObstacle($map, $newX, $newY)) {
                    $this->explorationMapService->registerExplorationData($explorationMap->id, $newX, $newY, 'obstacle');
                    $abortMessage = "Comando abortado para evitar colisión con obstáculo en las coordenadas ($newX, $newY)";
                    break; // Abortamos el comando si hay un obstáculo.
                }
                
                // Si no aborto, actualizamos la posición.
                $x = $newX;
                $y = $newY;
                $this->explorationMapService->registerExplorationData($explorationMap->id, $x, $y, 'explored');
            }
    
            $executedCommands[] = $command;
        }
    
        // Actualizamos la misión con los nuevos valores, o con el mensaje de abortar si aplica.
        $mission->update([
            'x' => $x,
            'y' => $y,
            'movements' => [...$mission->movements, [
                'position' => ['x' => $x, 'y' => $y],
                'commands' => implode('', $executedCommands)
            ]]
        ]);
    
        $rover->update(['direction' => $direction]);
    
        // Si aborto, retornamos el mensaje apropiado.
        if ($abortMessage) {
            return response()->json([
                'abort_message' => $abortMessage,
                'mission' => $mission
            ]);
        }
    
        // Si no aborto, retornamos la misión normalmente.
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
        return $x < 0 || $x > 200 || $y < 0 || $y > 200;
    }

    private function isObstacle($map, $x, $y)
    {
        $obstacles = json_decode($map->obstacles, true);
        return !empty(array_filter($obstacles, fn($obstacle) => $obstacle['x'] == $x && $obstacle['y'] == $y));
    }
}


