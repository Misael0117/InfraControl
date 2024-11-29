<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InftraControl | Configurar Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Configurar Usuarios</h2>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="home.php" class="btn btn-primary">Regresar</a>
        </div>
        <div class="d-flex justify-content-around mt-4">
            <button id="addUserBtn" class="btn btn-primary">Añadir Usuarios</button>
            <button id="listUserBtn" class="btn btn-secondary">Lista de Usuarios</button>
        </div>
    </div>

    <script>
        document.getElementById('addUserBtn').addEventListener('click', function() {
            window.location.href = 'añadir_usuario.php';
        });

        document.getElementById('listUserBtn').addEventListener('click', function() {
            window.location.href = 'lista_usuarios.php';
        });
    </script>
</body>
</html>
