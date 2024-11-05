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
    <!-- Incluir jQuery --> 
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <!-- Incluir SweetAlert2 --> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <button onclick="showContent('movimientos')" class="btn btn-warning">Ver Movimientos</button>
        </div>

        <!-- Área de contenido dinámico -->
        <div id="content-area" class="content-area mt-3">
            <p>Selecciona una opción para ver los detalles.</p>
        </div>
    </div>

    <script>
    function showContent(type) {
        const contentArea = document.getElementById("content-area");

        let url = "";
        if (type === "saldos") {
            url = "get_saldos.php"; 
        } else if (type === "entradas") {
            url = "get_entradas.php"; 
        } else if (type === "salidas") {
            url = "get_salidas.php"; 
        } else if (type === "movimientos") {
            url = "get_movimientos.php"; 
        }

        if (url) {
            $.ajax({
                url: url,
                method: "GET",
                success: function(response) {
                    contentArea.innerHTML = response;
                },
                error: function() {
                    contentArea.innerHTML = "<p>Error al cargar los datos.</p>";
                }
            });
        }
    }
</script>

</body>
</html>