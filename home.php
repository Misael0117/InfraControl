<?php 
session_start(); 
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
include 'user_info.php';
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>InfraControl | Home</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/home.css" rel="stylesheet">
    </head>
    <body>
        <div class="welcome-message">
            <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?>!!!</h1>
            <p>Selecciona una opción para comenzar</p>
        </div>
        
        <div class="options-container">
            <a href="inventario.php" class="option">Inventario</a>
            <a href="consultas.php" class="option">Consultas</a>
            <a href="reportes.php" class="option">Reportes</a>
            <a href="configurar_usuarios.php" class="option">Configurar Usuarios</a>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/I6ZZo9I/2eA2sLt2REzvg7vXs5DIBmLczCpDKw7i6oq0KVj0Ur9y8C4QIdbnF" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-a5j9ndqvS5hOxW9g6ziRaXWepdBLp6PT34xgjOgQfEjaz2xCW9mxgQaGQntm8D" crossorigin="anonymous"></script>
    </body>
</html>
