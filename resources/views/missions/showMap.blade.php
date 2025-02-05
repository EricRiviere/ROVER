<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body class="bg-dark text-light">
    <div class="container mt-5">
        <div class="card bg-secondary text-light shadow-lg p-4">
            <h2 class="text-center mb-4">Control de Rover</h2>

            <!-- Información del Rover -->
            <div id="roverInfo" class="mb-4 p-3 bg-dark rounded text-center">
                <h5>📍 Posición Actual</h5>
                <p id="coordinates">Cargando...</p>
                <h5>🧭 Dirección</h5>
                <p id="direction">Cargando...</p>
            </div>

            <form id="moveForm">
                @csrf
                <input type="hidden" id="missionId" value="{{ $mission->id }}">

                <div class="mb-3">
                    <label for="commands" class="form-label">Introduce comandos:</label>
                    <input type="text" id="commands" class="form-control bg-dark text-light border-light" placeholder="Ejemplo: FLRFFLLRR" required>
                </div>

                <div class="text-center mt-4">
                    <button type="button" id="sendCommands" class="btn btn-dark w-100">🚀 Enviar Comandos</button>
                </div>

                <div class="text-center mt-4">
                    <button type="button" id="finishMission" class="btn btn-danger w-100">🚨 Finalizar Misión</button>
                </div>                
            </form>

            <!-- Exploración de la Misión -->
            <div id="explorationData" class="mt-4 p-3 bg-dark rounded">
                <h5 class="text-center">🗺️ Datos de Exploración</h5>
                <div id="explorationGrid" class="d-flex justify-content-center flex-wrap" style="position: relative; width: 2000px; height: 2000px;">
                    <!-- El mapa de 200x200 celdas de 10x10px -->
                    <!-- Las celdas se generarán de forma dinámica -->
                </div>
            </div>
        </div>
    </div>
    <style>
        #explorationData {
            overflow-x: auto;
            max-width: 100%;
            white-space: nowrap;
        }
    
        #explorationGrid {
            position: relative;
            min-width: 2000px;
            min-height: 2000px;
            display: inline-block;
        }
    </style>
    <script>
        let missionId = document.getElementById("missionId").value;

        function fetchRoverInfo() {
            let missionData = {}; // Declarar missionData antes para que esté disponible en el catch

            fetch(`http://127.0.0.1:8000/api/missions/${missionId}`)
                .then(response => {
                    if (!response.ok) throw new Error("Error al obtener la misión");
                    return response.json();
                })
                .then(data => {
                    missionData = data; // Guardamos los datos de la misión
                    let { x, y, rover_id } = missionData;
                    document.getElementById("coordinates").innerText = `X: ${x}, Y: ${y}`;

                    return fetch(`http://127.0.0.1:8000/api/rovers/${rover_id}`);
                })
                .then(response => {
                    if (!response.ok) throw new Error("Error al obtener el rover");
                    return response.json();
                })
                .then(roverData => {
                    document.getElementById("direction").innerText = roverData.direction;
                    updateMap(roverData, missionData.x, missionData.y);
                })
                .catch(error => {
                    console.error("Error al obtener la información del rover:", error);

                    document.getElementById("coordinates").innerText =
                        `${missionData.x ?? "N/A"}, ${missionData.y ?? "N/A"}`;

                    document.getElementById("direction").innerText =
                        `${roverData.direction ?? "N/A"}`;
                });
        }

        function fetchExplorationData() {
            let explorationMapId = null;

            // Paso 1: Obtener el exploration_map_id de la misión
            fetch(`http://127.0.0.1:8000/api/missions/${missionId}`)
                .then(response => {
                    if (!response.ok) throw new Error("Error al obtener la misión");
                    return response.json();
                })
                .then(data => {
                    explorationMapId = data.exploration_map_id; // Guardamos el exploration_map_id
                    if (!explorationMapId) throw new Error("exploration_map_id no disponible");

                    // Paso 2: Usar explorationMapId para hacer fetch de los datos de exploración
                    return fetch(`http://127.0.0.1:8000/api/exploration-maps/${explorationMapId}/exploration-data`);
                })
                .then(response => {
                    if (!response.ok) throw new Error("Error al obtener los datos de exploración");
                    return response.json();
                })
                .then(explorationData => {
                    // Usar la función para actualizar los datos de exploración
                    updateExplorationData(explorationData);
                })
                .catch(error => {
                    console.error("Error al obtener los datos de exploración:", error);
                });
        }

        // Función para actualizar el mapa con la información de la exploración
        function updateExplorationData(explorationData) {
            const grid = document.getElementById("explorationGrid");

            // Iterar sobre cada celda de datos de exploración
            for (let x = 0; x < 201; x++) {
                for (let y = 0; y < 201; y++) {
                    // Crear una nueva celda
                    const cell = document.createElement("div");
                    cell.style.width = "10px";
                    cell.style.height = "10px";
                    cell.style.border = "1px solid #000";
                    cell.style.position = "absolute";
                    cell.style.left = `${x * 10}px`;
                    cell.style.top = `${(199 - y) * 10}px`;

                    // Verificar el estado de la celda
                    let cellStatus = "empty"; // Por defecto, las celdas son vacías
                    explorationData.forEach(item => {
                        if (item.x === x && item.y === y) {
                            cellStatus = item.type;
                        }
                    });

                    // Pintar las celdas
                    if (cellStatus === "explored") {
                        cell.style.backgroundColor = "green";
                    } else if (cellStatus === "obstacle") {
                        cell.style.backgroundColor = "red";
                    } else {
                        cell.style.backgroundColor = "white"; // Celda no explorada
                    }

                    grid.appendChild(cell);
                }
            }
        }

        // Función para actualizar la posición y dirección del rover en el mapa
        function updateMap(roverData, roverX, roverY) {
            const grid = document.getElementById("explorationGrid");

            // Crear el contorno de la celda del rover
            const roverCell = document.createElement("div");
            roverCell.style.position = "absolute";
            roverCell.style.left = `${roverX * 10}px`;
            cell.style.top = `${(199 - y) * 10}px`;

            // Pintar un triángulo representando la dirección del rover
            const triangle = document.createElement("div");
            triangle.style.width = "0";
            triangle.style.height = "0";
            triangle.style.borderLeft = "5px solid transparent";
            triangle.style.borderRight = "5px solid transparent";
            triangle.style.borderBottom = "10px solid white";

            // Ajuste de la dirección del rover
            switch (roverData.direction) {
                case 'N': // Norte
                    triangle.style.transform = "rotate(0deg)";
                    break;
                case 'E': // Este
                    triangle.style.transform = "rotate(90deg)";
                    break;
                case 'S': // Sur
                    triangle.style.transform = "rotate(180deg)";
                    break;
                case 'W': // Oeste
                    triangle.style.transform = "rotate(270deg)";
                    break;
            }

            roverCell.appendChild(triangle);
            grid.appendChild(roverCell);
        }

        // Cargar la información del rover y la exploración al inicio
        document.addEventListener("DOMContentLoaded", () => {
            fetchRoverInfo();
            fetchExplorationData();
        });

        // Enviar los comandos del rover
        document.getElementById("sendCommands").addEventListener("click", function() {
            let button = document.getElementById("sendCommands");
            let commands = document.getElementById("commands").value;

            button.innerHTML = "⏳ Enviando...";
            button.disabled = true;

            let data = { "commands": commands };

            fetch(`http://127.0.0.1:8000/api/missions/${missionId}/move`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) throw new Error("Error en la solicitud");
                return response.json();
            })
            .then(data => {
                if (data.abort_message) {
                    alert(`❌ ${data.abort_message}`);
                } else {
                    alert("✅ Comandos enviados con éxito");
                }
                document.getElementById("moveForm").reset();
                location.reload();
            })
            .catch(error => {
                alert("❌ Error al enviar los comandos.");
                console.error("Error:", error);
            })
            .finally(() => {
                button.innerHTML = "🚀 Enviar Comandos";
                button.disabled = false;
            });
        });

        // Finalizar misión
        document.getElementById("finishMission").addEventListener("click", function() {
            let button = document.getElementById("finishMission");

            button.innerHTML = "⏳ Finalizando...";
            button.disabled = true;

            // Realizar la solicitud de finalizar misión
            fetch(`http://127.0.0.1:8000/api/missions/${missionId}/finish`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                }
            })
            .then(response => {
                if (!response.ok) throw new Error("Error al finalizar la misión");
                return response.json();
            })
            .then(data => {
                alert("✅ Misión finalizada con éxito");
                window.location.href = "http://localhost:8000/missions/create"; // Redirigir a la página de creación de misiones
            })
            .catch(error => {
                alert("❌ Error al finalizar la misión.");
                console.error("Error:", error);
            })
            .finally(() => {
                button.innerHTML = "🚨 Finalizar Misión";
                button.disabled = false;
            });
        });

    </script>
</body>



