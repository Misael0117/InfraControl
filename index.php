
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>SMAPAC | InfraControl</title>
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" 
    integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
    <div class="row text-center login-page">
        <div class="col-md-12 login-form">
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-12 login-form-header">
                        <h1 class="display-2"><span>InfraControl</span></h1>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 login-from-row">
                        <input name="txt_uname" type="text" class="form-control" placeholder="Usuario" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 login-from-row">
                        <input name="txt_pwd" type="password" class="form-control" placeholder="Contraseña" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 login-from-row">
                        <button type="submit" class="btn btn-primary" name="but_submit">Entrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start();
include "config.php"; // Conectar con la base de datos

if(isset($_POST['but_submit'])){
    $username = $_POST['txt_uname'];
    $password = $_POST['txt_pwd'];
    if($username != "" && $password != ""){
        // Preparar la consulta con los parámetros
        $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE username = :username AND password = :password AND estado = 1");
        
        // Vincular los parámetros con los valores del formulario
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Ejecutar la consulta
        try {
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            if($record){
                // Iniciar sesión si los datos son correctos
                $_SESSION['usuario'] = $record['id'];
                echo "<script>
                    Swal.fire({
                        title: 'Usuario validado',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(function(){
                        window.location = 'home.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Usuario o contraseña incorrectos',
                        icon: 'error',
                        confirmButtonText: 'Intentar de nuevo'
                    });
                </script>";
            }
        } catch (PDOException $e) {
            // Mostrar mensaje de error si ocurre algún problema con la ejecución
            echo "Error al ejecutar la consulta: " . $e->getMessage();
        }
    }
}
?>