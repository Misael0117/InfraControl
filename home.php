
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InfraControl | Bienvenida</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Bienvenido a InfraControl</h1>
        <p>Hola, <?php echo $_SESSION['usuario'];?> ¡Inicio de sesion exitoso!</p>
        <a href="index.php" class="btn btn-primary">Cerrar Sesion</a>
    </div>
</body>
</html>
