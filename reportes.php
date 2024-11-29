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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfraControl | Generar Reportes</title>
    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/reportes.css">
</head>
<body>
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center">  
        <a href="home.php" class="btn btn-primary">Regresar</a> 
    </div>     
</div>
 

    <div class="container mt-5">
        <h1 class="text-center">Generar Reportes</h1>
        <div class="text-center mt-4">
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#reporteModal">
                Generar Reporte
            </button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reporteModal" tabindex="-1" role="dialog" aria-labelledby="reporteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reporteModalLabel">Generar Reporte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="generar_reporte.php" method="POST" target="_blank">
                        <div class="form-group">
                            <label for="tipoReporte">Seleccione el tipo de reporte:</label>
                            <select class="form-control" id="tipoReporte" name="tipoReporte" required>
                                <option value="semanal">Reporte Semanal</option>
                                <option value="mensual">Reporte Mensual</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fechaInicio">Fecha de Inicio:</label>
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaFin">Fecha de Fin:</label>
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-custom">Generar Reporte</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript de Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
