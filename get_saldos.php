<?php
session_start();
include 'config.php'; 

try {
    // Consulta a la tabla `saldos_material`
    $query = "SELECT fecha, saldo_anterior, cantidad_movida, saldo_actual, costo_promedio FROM saldos_material";
    $stmt = $conn->query($query);

    if ($stmt->rowCount() > 0) {  
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
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['fecha']}</td>
                    <td>{$row['saldo_anterior']}</td>
                    <td>{$row['cantidad_movida']}</td>
                    <td>{$row['saldo_actual']}</td>
                    <td>{$row['costo_promedio']}</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron registros de saldos en la base de datos.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error al ejecutar la consulta: " . $e->getMessage() . "</p>";
}