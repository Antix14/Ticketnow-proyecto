<?php
$host = "192.168.7.1"; 
$user = "usuario_admin";          
$pass = "asdf1234";
$db   = "ticketing";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>