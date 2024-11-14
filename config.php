<?php
// Configuración de la zona horaria
date_default_timezone_set('America/Mexico_City');

// Datos de conexión a la base de datos
$server = "localhost";
$username = "root";
$password = "";
$dbname = "infracontrol";

// Crear la conexión con MySQL usando PDO
try {
    $conn = new PDO("mysql:host=$server;dbname=$dbname", $username, $password);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('No se puede conectar a la base de datos: '. $e->getMessage());
}

// Función para cerrar la conexión a la base de datos
function closeConnection() {
    global $conn;
    $conn = null;
}

?>
