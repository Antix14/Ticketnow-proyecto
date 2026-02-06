<?php
$host = "192.168.7.1"; // Cambia esto por la IP real
$user = "usuario_admin";           // Usuario que creaste en la BD remota
$pass = "asdf1234";
$db   = "ticketing";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>