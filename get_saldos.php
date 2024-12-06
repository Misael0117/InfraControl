<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfraControl | Inventario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php
session_start();
include 'config.php';

try {
    // Verificar si existen registros en la tabla `saldos_material`
    $queryCheck = "SELECT COUNT(*) FROM saldos_material";
    $stmtCheck = $conn->query($queryCheck);
    $shouldInsertData = ($stmtCheck->fetchColumn() == 0);

    if ($shouldInsertData) {
        // Obtener las entradas desde `entrada_materiales`
        $queryEntradas = "SELECT cantidad, costo, fecha FROM entrada_materiales";
        $stmtEntradas = $conn->query($queryEntradas);

        // Obtener las salidas desde `salida_material`
        $querySalidas = "SELECT cantidad, fecha FROM salida_material";
        $stmtSalidas = $conn->query($querySalidas);

        // Inicializar variables para el cálculo del saldo
        $saldoAnterior = 0;

        // Recorrer las entradas y calcular el saldo actualizado
        while ($row = $stmtEntradas->fetch(PDO::FETCH_ASSOC)) {
            $fecha = $row['fecha'];
            $cantidadMovimiento = (float) $row['cantidad'];

            // Calcular el saldo actualizado para la entrada
            $nuevoSaldo = $saldoAnterior + $cantidadMovimiento;

            // Formatear los valores a dos decimales
            $saldoAnteriorFormateado = number_format($saldoAnterior, 2, '.', '');
            $cantidadMovimientoFormateada = number_format($cantidadMovimiento, 2, '.', '');
            $nuevoSaldoFormateado = number_format($nuevoSaldo, 2, '.', '');

            // Insertar en la tabla `saldos_material`
            $insertQuery = "INSERT INTO saldos_material (fecha, saldo_anterior, cantidad_movida, saldo_actual) 
                            VALUES (:fecha, :saldo_anterior, :cantidad_movida, :saldo_actual)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->execute([
                ':fecha' => $fecha,
                ':saldo_anterior' => $saldoAnteriorFormateado,
                ':cantidad_movida' => $cantidadMovimientoFormateada,
                ':saldo_actual' => $nuevoSaldoFormateado
            ]);

            // Actualizar el saldo anterior
            $saldoAnterior = $nuevoSaldo;
        }

        // Recorrer las salidas y calcular el saldo actualizado
        while ($row = $stmtSalidas->fetch(PDO::FETCH_ASSOC)) {
            $fecha = $row['fecha'];
            $cantidadMovimiento = (float) $row['cantidad'];

            // Calcular el saldo actualizado para la salida
            $nuevoSaldo = $saldoAnterior - $cantidadMovimiento;

            // Formatear los valores a dos decimales
            $saldoAnteriorFormateado = number_format($saldoAnterior, 2, '.', '');
            $cantidadMovimientoFormateada = number_format($cantidadMovimiento, 2, '.', '');
            $nuevoSaldoFormateado = number_format($nuevoSaldo, 2, '.', '');

            // Insertar en la tabla `saldos_material`
            $insertQuery = "INSERT INTO saldos_material (fecha, saldo_anterior, cantidad_movida, saldo_actual) 
                            VALUES (:fecha, :saldo_anterior, :cantidad_movida, :saldo_actual)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->execute([
                ':fecha' => $fecha,
                ':saldo_anterior' => $saldoAnteriorFormateado,
                ':cantidad_movida' => '-'.$cantidadMovimientoFormateada,
                ':saldo_actual' => $nuevoSaldoFormateado
            ]);

            // Actualizar el saldo anterior
            $saldoAnterior = $nuevoSaldo;
        }

        echo '<script>Swal.fire("Éxito", "Datos insertados en la tabla saldos_material correctamente.", "success");</script>';
    }

    // Mostrar la tabla de saldos al usuario
    $querySaldos = "SELECT * FROM saldos_material ORDER BY fecha";
    $stmtSaldos = $conn->query($querySaldos);

    echo "<div class='container mt-3 animate__animated animate__fadeIn'>";
    echo "<table class='table table-striped'>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Saldo Anterior</th>
                    <th>Cantidad Movida</th>
                    <th>Saldo Actual</th>
                </tr>
            </thead>
            <tbody>";
    
    while ($row = $stmtSaldos->fetch(PDO::FETCH_ASSOC)) {
        $fecha = $row['fecha'];
        $saldoAnterior = $row['saldo_anterior'];
        $cantidadMovida = $row['cantidad_movida'];
        $saldoActual = $row['saldo_actual'];

        echo "<tr>
                <td>{$fecha}</td>
                <td>{$saldoAnterior}</td>
                <td>{$cantidadMovida}</td>
                <td>{$saldoActual}</td>
              </tr>";
    }

    echo "</tbody></table>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<p>Error al ejecutar la consulta: " . $e->getMessage() . "</p>";
}
?>

</body>
</html>
