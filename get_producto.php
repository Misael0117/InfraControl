<?php
include 'config.php';

$query = "SELECT id, clave, producto, categoria FROM catalago_productos";
$result = $conn->query($query);

echo "<button class='btn btn-primary' onclick='loadAddProductForm()'>Añadir Producto</button>";

if ($result->rowCount() > 0) {
    echo "<table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Clave</th>
                    <th>Producto</th>
                    <th>Categoria</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['clave']}</td>
                <td>{$row['producto']}</td>
                <td>{$row['categoria']}</td>
                <td>
                    <button onclick='modifyProduct({$row['id']})' class='btn btn-warning btn-sm'>Editar</button>
                    <button onclick='removeProduct({$row['id']})' class='btn btn-danger btn-sm'>Borrar</button>
                </td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No se encontraron productos.</p>";
}
?>
