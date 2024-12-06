<?php
session_start();
include 'config.php'; 

try {
    // Consulta a la tabla `entrada_materiales`
    $query = "SELECT id, categoria, producto, factura, proveedor, cantidad, costo, fecha FROM entrada_materiales";
    $stmt = $conn->query($query);

    echo "<button class='btn btn-primary' onclick='loadAddEntryForm()'>Añadir Entrada</button>";

    if ($stmt->rowCount() > 0) {  
        echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Producto</th>
                        <th>Factura</th>
                        <th>Proveedor</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['categoria']}</td>
                    <td>{$row['producto']}</td>
                    <td>{$row['factura']}</td>
                    <td>{$row['proveedor']}</td>
                    <td>{$row['cantidad']}</td>
                    <td>{$row['costo']}</td>
                    <td>{$row['fecha']}</td>
                    <td>
                        <button class='btn btn-warning btn-sm' onclick='modifyRecord(\"entrada\", {$row['id']})'>Editar</button>
                        <button class='btn btn-danger btn-sm' onclick='removeEntry(\"entrada\", {$row['id']})'>Eliminar</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron registros de entradas en la base de datos.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error al ejecutar la consulta: " . $e->getMessage() . "</p>";
}
?>
