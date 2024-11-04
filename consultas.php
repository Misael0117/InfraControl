<?php 
session_start(); 
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
include 'user_info.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InfraControl | Consultas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/consultas.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Información del usuario y botón de regreso al inicio -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="home.php" class="btn btn-primary">Regresar</a>
        </div>

        <!-- Botones para consultas -->
        <div class="text-center mt-4">
            <button onclick="showContent('saldos')" class="btn btn-info">Ver Saldos</button>
            <button onclick="showContent('entradas')" class="btn btn-success">Ver Entradas</button>
            <button onclick="showContent('salidas')" class="btn btn-danger">Ver Salidas</button>
        </div>

        <!-- Área de contenido dinámico -->
        <div id="content-area" class="content-area mt-3">
            <p>Selecciona una opción para ver los detalles.</p>
        </div>
    </div>

    <script>
        // Función para cargar el contenido dinámico según el botón seleccionado
        function showContent(type) {
            const contentArea = document.getElementById("content-area");

            if (type === "saldos") {
                contentArea.innerHTML = "<h4>Saldos</h4><p>Aquí se mostrarán los saldos...</p>";
            } else if (type === "entradas") {
                contentArea.innerHTML = "<h4>Entradas</h4><p>Aquí se mostrarán las entradas...</p>";
            } else if (type === "salidas") {
                contentArea.innerHTML = "<h4>Salidas</h4><p>Aquí se mostrarán las salidas...</p>";
            }
        }
    </script>
</body>
</html>
