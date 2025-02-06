<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<body class="bg-dark text-light">
    <div class="container mt-5">
        <div class="row align-items-center">
            <!-- Imagen de Marte -->
            <div class="col-lg-6 text-center mb-4 d-lg-block d-none">
                <img src="https://cdn.pixabay.com/photo/2012/11/28/09/08/mars-67522_1280.jpg" alt="Imagen de Marte" class="img-fluid rounded shadow">
            </div>

            <!-- Formulario -->
            <div class="col-lg-6">
                <div class="card bg-secondary text-light shadow-lg p-4">
                    <h2 class="text-center mb-4">Iniciar Nueva Misión</h2>

                    <!-- Imagen en pantallas pequeñas -->
                    <div class="text-center d-lg-none mb-4">
                        <img src="https://cdn.pixabay.com/photo/2012/11/28/09/08/mars-67522_1280.jpg" alt="Imagen de Marte" class="img-fluid rounded shadow">
                    </div>

                    <form id="missionForm">
                        @csrf

                        <div class="mb-3">
                            <label for="rover_id" class="form-label">Selecciona un Rover:</label>
                            <select id="rover_id" class="form-select bg-dark text-light border-light">
                                @foreach($rovers as $rover)
                                    <option value="{{ $rover->id }}">{{ $rover->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="map_id" class="form-label">Selecciona un Mapa:</label>
                            <select id="map_id" class="form-select bg-dark text-light border-light">
                                @foreach($maps as $map)
                                    <option value="{{ $map->id }}">{{ $map->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="x" class="form-label">Coordenada X:</label>
                                <input type="number" id="x" class="form-control bg-dark text-light border-light" min="0" max="200" required>
                            </div>
                            <div class="col-md-6">
                                <label for="y" class="form-label">Coordenada Y:</label>
                                <input type="number" id="y" class="form-control bg-dark text-light border-light" min="0" max="200" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="direction" class="form-label">Dirección Inicial:</label>
                            <select id="direction" class="form-select bg-dark text-light border-light">
                                <option value="N">Norte (N)</option>
                                <option value="S">Sur (S)</option>
                                <option value="E">Este (E)</option>
                                <option value="W">Oeste (W)</option>
                            </select>
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" id="startMission" class="btn btn-dark w-100">🚀 Iniciar Misión</button>
                        </div>
                        <div class="text-center mt-4 mb-4">
                            <a href="http://localhost:8000/dashboard" class="btn btn-dark w-100">Volver al Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("startMission").addEventListener("click", function() {
            let button = document.getElementById("startMission");
            button.innerHTML = "🚀 Enviando...";
            button.disabled = true;

            let roverId = document.getElementById("rover_id").value;
            let direction = document.getElementById("direction").value;

            // Actualizar dirección del rover
            fetch(`http://127.0.0.1:8000/api/rovers/${roverId}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify({ direction: direction })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error al actualizar la dirección del rover");
                }
                return response.json();
            })
            .then(() => {
                // Si la actualización es exitosa, crear la misión
                let data = {
                    rover_id: roverId,
                    map_id: document.getElementById("map_id").value,
                    x: document.getElementById("x").value,
                    y: document.getElementById("y").value
                };

                return fetch("http://127.0.0.1:8000/api/missions", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                    },
                    body: JSON.stringify(data)
                });
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en la solicitud de la misión");
                }
                return response.json();
            })
            .then(data => {
                let missionId = data.id; // Obtener el ID de la misión creada
                window.location.href = `/mission/${missionId}/map`; // Redirigir a showMap.blade.php
            })
            .catch(error => {
                alert("❌ Error al iniciar la misión.");
                console.error("Error:", error);
            })
            .finally(() => {
                button.innerHTML = "🚀 Iniciar Misión";
                button.disabled = false;
            });
        });
    </script>    
</body>
