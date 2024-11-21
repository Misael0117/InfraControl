<?php
session_start();
include 'config.php';

try {
    // Obtener las entradas desde `entrada_materiales`
    $queryEntradas = "SELECT cantidad, costo, fecha FROM entrada_materiales";
    $stmtEntradas = $conn->query($queryEntradas);

    // Obtener las salidas desde `salida_material`
    $querySalidas = "SELECT cantidad, fecha FROM salida_material";
    $stmtSalidas = $conn->query($querySalidas);

    // Inicializar variables para el cálculo del saldo
    $saldoAnterior = 0;
    $costoPromedio = 0;

    // Recorrer las entradas y calcular el saldo actualizado
    echo "<table class='table table-striped'>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Saldo Anterior</th>
                    <th>Cantidad Movida</th>
                    <th>Saldo Actual</th>
                    <th>Costo Promedio</th>
                </tr>
            </thead>
            <tbody>";
    
    while ($row = $stmtEntradas->fetch(PDO::FETCH_ASSOC)) {
        $fecha = $row['fecha'];
        $cantidadMovimiento = (float) $row['cantidad'];
        $costoMovimiento = (float) $row['costo'];

        // Calcular el saldo actualizado para la entrada
        $nuevoSaldo = $saldoAnterior + $cantidadMovimiento;
        $costoPromedio = (($saldoAnterior * $costoPromedio) + ($cantidadMovimiento * $costoMovimiento)) / ($saldoAnterior + $cantidadMovimiento);

        // Formatear los valores a dos decimales
        $saldoAnteriorFormateado = number_format($saldoAnterior, 2, '.', '');
        $cantidadMovimientoFormateada = number_format($cantidadMovimiento, 2, '.', '');
        $nuevoSaldoFormateado = number_format($nuevoSaldo, 2, '.', '');
        $costoPromedioFormateado = number_format($costoPromedio, 2, '.', '');

        // Insertar en la tabla `saldos_material`
        $insertQuery = "INSERT INTO saldos_material (fecha, saldo_anterior, cantidad_movida, saldo_actual, costo_promedio) 
                        VALUES (:fecha, :saldo_anterior, :cantidad_movida, :saldo_actual, :costo_promedio)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->execute([
            ':fecha' => $fecha,
            ':saldo_anterior' => $saldoAnteriorFormateado,
            ':cantidad_movida' => $cantidadMovimientoFormateada,
            ':saldo_actual' => $nuevoSaldoFormateado,
            ':costo_promedio' => $costoPromedioFormateado
        ]);

        // Mostrar la entrada en la tabla
        echo "<tr>
                <td>{$fecha}</td>
                <td>{$saldoAnteriorFormateado}</td>
                <td>+{$cantidadMovimientoFormateada}</td>
                <td>{$nuevoSaldoFormateado}</td>
                <td>{$costoPromedioFormateado}</td>
              </tr>";
        
        // Actualizar el saldo anterior
        $saldoAnterior = $nuevoSaldo;
    }

    // Recorrer las salidas y calcular el saldo actualizado
    while ($row = $stmtSalidas->fetch(PDO::FETCH_ASSOC)) {
        $fecha = $row['fecha'];
        $cantidadMovimiento = (float) $row['cantidad'];
        $costoMovimiento = $costoPromedio;  // Para las salidas usamos el costo promedio existente

        // Calcular el saldo actualizado para la salida
        $nuevoSaldo = $saldoAnterior - $cantidadMovimiento;
        if ($nuevoSaldo != 0) {
            $costoPromedio = (($saldoAnterior * $costoPromedio) - ($cantidadMovimiento * $costoMovimiento)) / $nuevoSaldo;
        }

        // Formatear los valores a dos decimales
        $saldoAnteriorFormateado = number_format($saldoAnterior, 2, '.', '');
        $cantidadMovimientoFormateada = number_format($cantidadMovimiento, 2, '.', '');
        $nuevoSaldoFormateado = number_format($nuevoSaldo, 2, '.', '');
        $costoPromedioFormateado = number_format($costoPromedio, 2, '.', '');

        // Insertar en la tabla `saldos_material`
        $insertQuery = "INSERT INTO saldos_material (fecha, saldo_anterior, cantidad_movida, saldo_actual, costo_promedio) 
                        VALUES (:fecha, :saldo_anterior, :cantidad_movida, :saldo_actual, :costo_promedio)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->execute([
            ':fecha' => $fecha,
            ':saldo_anterior' => $saldoAnteriorFormateado,
            ':cantidad_movida' => '-'.$cantidadMovimientoFormateada,
            ':saldo_actual' => $nuevoSaldoFormateado,
            ':costo_promedio' => $costoPromedioFormateado
        ]);

        // Mostrar la salida en la tabla
        echo "<tr>
                <td>{$fecha}</td>
                <td>{$saldoAnteriorFormateado}</td>
                <td>-{$cantidadMovimientoFormateada}</td>
                <td>{$nuevoSaldoFormateado}</td>
                <td>{$costoPromedioFormateado}</td>
              </tr>";
        
        // Actualizar el saldo anterior
        $saldoAnterior = $nuevoSaldo;
    }

    echo "</tbody></table>";

} catch (PDOException $e) {
    echo "<p>Error al ejecutar la consulta: " . $e->getMessage() . "</p>";
}
?>
