<?php
include 'config.php'; // Conexión a la base de datos

$query = "SELECT id, proveedor FROM catalago_proveedor";
$result = $conn->query($query);

echo "<button class='btn btn-primary' onclick='loadAddProveedorForm()'>Añadir Proveedor</button>";

if ($result->rowCount() > 0) {
    echo "<table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Asegurarse de que los datos estén completos y correctos
        if (!empty($row['id']) && !empty($row['proveedor'])) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['proveedor']}</td>
                    <td>
                        <button onclick='modifyProveedor({$row['id']})' class='btn btn-warning btn-sm'>Editar</button>
                        <button onclick='removeProveedor({$row['id']})' class='btn btn-danger btn-sm'>Borrar</button>
                    </td>
                  </tr>";
        }
    }
    echo "</tbody></table>";
} else {
    echo "<p>No se encontraron proveedores.</p>";
}
?>
