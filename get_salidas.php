<?php
session_start();
include 'config.php'; 

try {
    // Consulta a la tabla `salida_material`
    $query = "SELECT id, categoria, producto, fecha_salida, producto_id, folio, supervisor, colonia, calle, usuario, contrato, medidor, cantidad, costo, fecha FROM salida_material";
    $stmt = $conn->query($query);

    if ($stmt->rowCount() > 0) {  
        echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Producto</th>
                        <th>Fecha de Salida</th>
                        <th>Folio</th>
                        <th>Supervisor</th>
                        <th>Colonia</th>
                        <th>Calle</th>
                        <th>Usuario</th>
                        <th>Contrato</th>
                        <th>Medidor</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['categoria']}</td>
                    <td>{$row['producto']}</td>
                    <td>{$row['fecha_salida']}</td>
                    <td>{$row['folio']}</td>
                    <td>{$row['supervisor']}</td>
                    <td>{$row['colonia']}</td>
                    <td>{$row['calle']}</td>
                    <td>{$row['usuario']}</td>
                    <td>{$row['contrato']}</td>
                    <td>{$row['medidor']}</td>
                    <td>{$row['cantidad']}</td>
                    <td>{$row['costo']}</td>
                    <td>{$row['fecha']}</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron registros de salidas en la base de datos.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error al ejecutar la consulta: " . $e->getMessage() . "</p>";
}
?>