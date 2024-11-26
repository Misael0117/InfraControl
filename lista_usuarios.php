<?php
require('config.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
<div class="mt-3">
    <a href="configurar_usuarios.php" class="btn btn-secondary">Regresar</a>
</div>
<div class="container mt-5">
    <h2>Lista de Usuarios</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Nivel</th>
                <th>Estado</th>
                <th>Última Conexión</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, nombre, username, user_level, estado, last_connection FROM usuarios";
            $stmt = $conn->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Determinar el estado del usuario
                $estado = $row['estado'] == 1 ? 'Activo' : 'Inactivo';

                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['user_level'] . "</td>";
                echo "<td>" . $estado . "</td>";
                echo "<td>" . $row['last_connection'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
