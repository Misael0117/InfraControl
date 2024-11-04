<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
<div class="user-info">
        <img class = "user-image" src="img/usuario.png" alt="Usuario">
        <span><?php echo $_SESSION['usuario']; ?></span>
        <?php if (basename($_SERVER['PHP_SELF']) == 'home.php'): ?>
            <a class="logout-btn" href="index.php">Cerrar Sesión</a>
            <?php endif; ?>
    </div>

<style>    
    /* Estilo para el icono de usuario y nombre */
.user-info {
        position: fixed;
        top: 10px;
        left: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-align: left
}

.user-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-bottom: 5px; /* Espacio entre imagen y texto */
}

.user-info .logout-btn {
    display: block;
    margin-top: 8px; /* Espacio entre nombre y botón */
    padding: 6px 12px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 12px;
    transition: background-color 0.3s ease;
}

.user-info .logout-btn:hover {
    background-color: #0056b3;
}


</style>