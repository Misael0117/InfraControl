<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>SMAPAC | Iniciar Sesión</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="row text-center login-page">
            <div class="col-md-12 login-form">
                <form class="animate__animated animate__fadeIn">
                    <h1 class="display-4">Iniciar Sesión</h1>
                    <div class="form-group">
                        <input name="txt_uname" type="text" class="form-control" placeholder="Usuario" required/>
                    </div>
                    <div class="form-group">
                        <input name="txt_pwd" type="password" class="form-control" placeholder="Contraseña" required/>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="login()">Entrar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function login() {
            Swal.fire({
                title: 'Login Exitoso',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(function() {
                window.location = 'home.php';
            });
        }
    </script>
</body>
</html>
