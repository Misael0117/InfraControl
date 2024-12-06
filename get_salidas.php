<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfraControl | Salidas</title>
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
    // Consulta a la tabla `salida_material`
    $query = "SELECT id, categoria, producto, folio, supervisor, colonia, calle, usuario, contrato, medidor, cantidad, costo, fecha FROM salida_material";
    $stmt = $conn->query($query);

    echo "<div class='container mt-4'>";
    echo "<button class='btn btn-primary mb-3' onclick='loadAddSalidaForm()'>Añadir Salida</button>";

    if ($stmt->rowCount() > 0) {  
        echo "<table class='table table-hover table-bordered'>
                <thead class='thead-dark'>
                    <tr>
                        <th>Categoría</th>
                        <th>Producto</th>
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>{$row['categoria']}</td>
                    <td>{$row['producto']}</td>
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
                    <td>
                        <button class='btn btn-warning btn-sm' onclick='editEntry(\"salida\", {$row['id']})'>Editar</button>
                        <button class='btn btn-danger btn-sm' onclick='deleteEntry(\"salida\", {$row['id']})'>Eliminar</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='alert alert-info'>No se encontraron registros de salidas en la base de datos.</p>";
    }
    echo "</div>";
} catch (PDOException $e) {
    echo "<p class='alert alert-danger'>Error al ejecutar la consulta: " . $e->getMessage() . "</p>";
}
?>

</body>
</html>
