<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfraControl | Añadir Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        .eye-icon {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 10px;
        }
        .form-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Añadir Usuario</h2>
        <form id="addUserForm" action="añadir_usuario.php" method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3 form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <span class="eye-icon" onclick="togglePasswordVisibility('password')">&#128065;</span>
            </div>
            <div class="mb-3 form-group">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <span class="eye-icon" onclick="togglePasswordVisibility('confirm_password')">&#128065;</span>
            </div>
            <div class="mb-3">
                <label for="user_level" class="form-label">Nivel de Usuario</label>
                <input type="number" class="form-control" id="user_level" name="user_level" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
        <div class="mt-3">
            <a href="configurar_usuarios.php" class="btn btn-secondary">Regresar</a>
        </div>
    </div>

    <script>
        document.getElementById('addUserForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden');
                event.preventDefault();
            }
        });

        function togglePasswordVisibility(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require('config.php'); // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_level = $_POST['user_level'];
    $estado = $_POST['estado'];

    // No encriptamos la contraseña por el momento

    // Consulta SQL para insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, username, password, user_level, estado) 
            VALUES (:nombre, :username, :password, :user_level, :estado)";
    $stmt = $conn->prepare($sql);
    
    // Vincular los parámetros
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password); // Contraseña sin encriptar
    $stmt->bindParam(':user_level', $user_level);
    $stmt->bindParam(':estado', $estado);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Usuario creado exitosamente.";
    } else {
        echo "Error al crear el usuario.";
    }
}
?>
