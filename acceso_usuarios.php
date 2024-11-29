<?php
session_start();

// Redirigir si no hay sesión iniciada
if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

// Función para verificar permisos
function checkPermission($required_level) {
    if ($_SESSION['user_level'] > $required_level) {
        echo "<script>alert('No tienes permiso para acceder a esta página.'); window.location.href='home.php';</script>";
        exit;
    }
}
?>
