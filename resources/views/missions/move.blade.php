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
                <input type="hidden" id="missionId" value="{{ $missionId }}">

                <div class="mb-3">
                    <label for="commands" class="form-label">Introduce comandos:</label>
                    <input type="text" id="commands" class="form-control bg-dark text-light border-light" placeholder="Ejemplo: FLRFFLLRR" required>
                </div>

                <div class="text-center mt-4">
                    <button type="button" id="sendCommands" class="btn btn-dark w-100">🚀 Enviar Comandos</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let missionId = document.getElementById("missionId").value;

        function fetchRoverInfo() {
            fetch(`http://127.0.0.1:8000/api/missions/${missionId}`)
            .then(response => {
                if (!response.ok) throw new Error("Error al obtener la misión");
                return response.json();
            })
            .then(missionData => {
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
            })
            .catch(error => {
                console.error("Error al obtener la información del rover:", error);
                document.getElementById("coordinates").innerText = "Error al cargar datos";
                document.getElementById("direction").innerText = "Error al cargar datos";
            });
        }

        // Cargar la información al inicio
        document.addEventListener("DOMContentLoaded", fetchRoverInfo);

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
                // Verificamos si hay un mensaje de aborto
                if (data.abort_message) {
                    alert(`❌ ${data.abort_message}`); // Mostramos el mensaje de aborto
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
    </script>
</body>



