<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body class="bg-dark text-light">
    <div class="container mt-5">
        <h1 class="text-center">Panel de Control - Exploración Espacial</h1>
        
        <div class="accordion mt-4" id="explorationAccordion">
            
            <!-- MAPS -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMaps">
                    <button class="accordion-button bg-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#mapsSection">
                        MAPS
                    </button>
                </h2>
                <div id="mapsSection" class="accordion-collapse collapse show" data-bs-parent="#explorationAccordion">
                    <div class="accordion-body bg-dark">
                        <h3>Create Map</h3>
                        <form id="createMapForm">
                            <div class="mb-3">
                                <label for="mapName" class="form-label text-light">Nombre del Mapa</label>
                                <input type="text" class="form-control bg-secondary text-light" id="mapName" required>
                            </div>
                            <button type="button" class="btn btn-light" onclick="createMap()">Crear Mapa</button>
                        </form>
                        <hr>
                        <h3>Get One Map</h3>
                        <form id="getOneMapForm">
                            <div class="mb-3">
                                <label for="mapId" class="form-label text-light">ID del Mapa</label>
                                <input type="number" class="form-control bg-secondary text-light" id="mapId" required>
                            </div>
                            <button type="button" class="btn btn-light" onclick="getOneMap()">Obtener Mapa</button>
                        </form>
                        <pre id="mapResult" class="bg-light text-dark p-3 mt-3"></pre>
                        <hr>
                        <h3>Get All Maps</h3>
                        <button class="btn btn-light" onclick="getAllMaps()">Obtener Todos los Mapas</button>
                        <pre id="allMapsResult" class="bg-light text-dark p-3 mt-3"></pre>
                    </div>
                </div>
            </div>
            
            <!-- ROVERS -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRovers">
                    <button class="accordion-button bg-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#roversSection">
                        ROVERS
                    </button>
                </h2>
                <div id="roversSection" class="accordion-collapse collapse" data-bs-parent="#explorationAccordion">
                    <div class="accordion-body bg-dark">
                        <h3>Create Rover</h3>
                        <form id="createRoverForm">
                            <div class="mb-3">
                                <label for="roverName" class="form-label text-light">Nombre del Rover</label>
                                <input type="text" class="form-control bg-secondary text-light" id="roverName" required>
                            </div>
                            <button type="button" class="btn btn-light" onclick="createRover()">Crear Rover</button>
                        </form>
                        <hr>
                        <h3>Get All Rovers</h3>
                        <button class="btn btn-light" onclick="getAllRovers()">Obtener Todos los Rovers</button>
                        <pre id="allRoversResult" class="bg-light text-dark p-3 mt-3"></pre>
                    </div>
                </div>
            </div>
            
            <!-- MISSIONS -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingMissions">
                    <button class="accordion-button bg-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#missionsSection">
                        MISSIONS
                    </button>
                </h2>
                <div id="missionsSection" class="accordion-collapse collapse" data-bs-parent="#explorationAccordion">
                    <div class="accordion-body bg-dark">
                        <h3>Get One Mission</h3>
                        <form id="getOneMissionForm">
                            <div class="mb-3">
                                <label for="missionId" class="form-label text-light">Mission ID</label>
                                <input type="number" class="form-control bg-secondary text-light" id="missionId" required>
                            </div>
                            <button type="button" class="btn btn-light" onclick="getOneMission()">Obtener Misión</button>
                        </form>
                        <pre id="missionResult" class="bg-light text-dark p-3 mt-3"></pre>
                        <hr>
                        <h3>Get All Missions</h3>
                        <button class="btn btn-light" onclick="getAllMissions()">Obtener Todas las Misiones</button>
                        <pre id="allMissionsResult" class="bg-light text-dark p-3 mt-3"></pre>
                    </div>
                </div>
            </div>
            
            <!-- EXPLORATION MAPS -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingExplorationMaps">
                    <button class="accordion-button bg-secondary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#explorationMapsSection">
                        EXPLORATION MAPS
                    </button>
                </h2>
                <div id="explorationMapsSection" class="accordion-collapse collapse" data-bs-parent="#explorationAccordion">
                    <div class="accordion-body bg-dark">
                        <h3>Get One Exploration Map</h3>
                        <form id="getOneExplorationMapForm">
                            <div class="mb-3">
                                <label for="explorationMapId" class="form-label text-light">Exploration Map ID</label>
                                <input type="number" class="form-control bg-secondary text-light" id="explorationMapId" required>
                            </div>
                            <button type="button" class="btn btn-light" onclick="getOneExplorationMap()">Obtener Mapa de Exploración</button>
                        </form>
                        <pre id="explorationMapResult" class="bg-light text-dark p-3 mt-3"></pre>
                        <hr>
                        <h3>Get All Exploration Maps</h3>
                        <button class="btn btn-light" onclick="getAllExplorationMaps()">Obtener Todos los Mapas de Exploración</button>
                        <pre id="allExplorationMapsResult" class="bg-light text-dark p-3 mt-3"></pre>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4 mb-4">
                <a href="http://localhost:8000/missions/create" class="btn btn-light">Ir a crear misión</a>
            </div>
        </div>
    </div>
</body>
    
    <script>
        function createMap() {
            let name = document.getElementById("mapName").value;
            fetch("http://127.0.0.1:8000/api/maps", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ name: name, width: 200, height: 200 })
            })
            .then(response => response.json())
            .then(data => alert("Mapa creado con éxito!"))
            .catch(error => alert("Error al crear el mapa"));
        }
        
        function getOneMap() {
            let mapId = document.getElementById("mapId").value;
            fetch(`http://127.0.0.1:8000/api/maps/${mapId}`)
            .then(response => response.json())
            .then(data => document.getElementById("mapResult").textContent = JSON.stringify(data, null, 2))
            .catch(error => alert("Error al obtener el mapa"));
        }

        function getAllMaps() {
            fetch("http://127.0.0.1:8000/api/maps")
            .then(response => response.json())
            .then(data => document.getElementById("allMapsResult").textContent = JSON.stringify(data, null, 2))
            .catch(error => alert("Error al obtener los mapas"));
        }

        function createRover() {
            let name = document.getElementById("roverName").value;
            fetch("http://127.0.0.1:8000/api/rovers", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ name: name, x: 0, y: 0, direction: "N", status: "inactive", map_id: 1})
            })
            .then(response => response.json())
            .then(data => alert("Rover creado con éxito!"))
            .catch(error => alert("Error al crear el rover"));
        }

        function getAllRovers() {
            fetch("http://127.0.0.1:8000/api/rovers")
            .then(response => response.json())
            .then(data => document.getElementById("allRoversResult").textContent = JSON.stringify(data, null, 2))
            .catch(error => alert("Error al obtener los rovers"));
        }

        function getOneMission() {
            let missionId = document.getElementById("missionId").value;
            fetch(`http://127.0.0.1:8000/api/missions/${missionId}`)
            .then(response => response.json())
            .then(data => document.getElementById("missionResult").textContent = JSON.stringify(data, null, 2))
            .catch(error => alert("Error al obtener la mision"));
        }

        function getAllMissions() {
            fetch("http://127.0.0.1:8000/api/missions")
            .then(response => response.json())
            .then(data => document.getElementById("allMissionsResult").textContent = JSON.stringify(data, null, 2))
            .catch(error => alert("Error al obtener las misiones"));
        }

        function getOneExplorationMap() {
            let explorationMapId = document.getElementById("explorationMapId").value;
            fetch(`http://127.0.0.1:8000/api/exploration-maps/${explorationMapId}`)
            .then(response => response.json())
            .then(data => document.getElementById("explorationMapResult").textContent = JSON.stringify(data, null, 2))
            .catch(error => alert("Error al obtener el mapa de exploratcion"));
        }

        function getAllExplorationMaps() {
            fetch("http://127.0.0.1:8000/api/exploration-maps")
            .then(response => response.json())
            .then(data => document.getElementById("allExplorationMapsResult").textContent = JSON.stringify(data, null, 2))
            .catch(error => alert("Error al obtener los mapas de exploracion"));
        }
    </script>
</body>