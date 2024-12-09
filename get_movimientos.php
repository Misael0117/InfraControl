<?php
session_start();
include 'config.php'; // Conexión a la base de datos

try {
    // Consulta para obtener datos de `entrada_materiales`
    $queryEntradas = "SELECT 'Entrada' as tipo_movimiento, producto, cantidad, costo, fecha FROM entrada_materiales";

    // Consulta para obtener datos de `salida_material`
    $querySalidas = "SELECT 'Salida' as tipo_movimiento, producto, cantidad, costo, fecha FROM salida_material";

    // Unir ambas consultas
    $query = "$queryEntradas UNION ALL $querySalidas ORDER BY fecha";

    $stmt = $conn->query($query);

    if ($stmt->rowCount() > 0) {
        // Preparar la consulta para insertar en `movimientos_inventario` si no existe
        $insertQuery = "INSERT INTO movimientos_inventario (tipo_movimiento, producto, cantidad, costo, fecha)
                        SELECT :tipo_movimiento, :producto, :cantidad, :costo, :fecha
                        WHERE NOT EXISTS (
                            SELECT 1 FROM movimientos_inventario 
                            WHERE tipo_movimiento = :tipo_movimiento AND producto = :producto AND cantidad = :cantidad 
                            AND costo = :costo AND fecha = :fecha
                        )";
        $insertStmt = $conn->prepare($insertQuery);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Insertar los datos si no existen
            $insertStmt->execute([
                ':tipo_movimiento' => $row['tipo_movimiento'],
                ':producto' => $row['producto'],
                ':cantidad' => $row['cantidad'],
                ':costo' => $row['costo'],
                ':fecha' => $row['fecha']
            ]);
        }

        // Mostrar la tabla al usuario
        echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Tipo de Movimiento</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>";
        $stmt->execute(); // Reejecutar la consulta para mostrar datos

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['tipo_movimiento']}</td>
                    <td>{$row['producto']}</td>
                    <td>{$row['cantidad']}</td>
                    <td>$ {$row['costo']}</td>
                    <td>{$row['fecha']}</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron registros en el historial.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error al ejecutar la consulta: " . $e->getMessage() . "</p>";
}
?>
