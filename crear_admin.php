<?php
include 'db.php'; // Tu conexión a la BD

$nombre = "admin_ticketnow";
$email = "admin@ticketnow.com";
$password = "asdf1234"; // Contraseña que quieras
$rol = "admin";

// Encriptar la contraseña
$hash = password_hash($password, PASSWORD_BCRYPT);

// Insertar usuario en la base de datos
$sql = "INSERT INTO usuarios (nombre, email, contraseña, rol) 
        VALUES ('$nombre', '$email', '$hash', '$rol')";

if(mysqli_query($conexion, $sql)){
    echo "Usuario admin creado con éxito.<br>";
    echo "Email: $email<br>Contraseña: $password";
} else {
    echo "Error: " . mysqli_error($conexion);
}
?>
