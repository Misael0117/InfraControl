<?php
session_start();
include 'config.php'; 

try {
    // Consulta para obtener datos de `entrada_materiales`
    $queryEntradas = "SELECT id, 'Entrada' as tipo_movimiento, producto, cantidad, costo, fecha FROM entrada_materiales";
    
    // Consulta para obtener datos de `salida_material`
    $querySalidas = "SELECT id, 'Salida' as tipo_movimiento, producto, cantidad, costo, fecha FROM salida_material";

    // Unir ambas consultas
    $query = "$queryEntradas UNION ALL $querySalidas ORDER BY fecha";

    $stmt = $conn->query($query);

    if ($stmt->rowCount() > 0) {  
        echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo de Movimiento</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['id']}</td>
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