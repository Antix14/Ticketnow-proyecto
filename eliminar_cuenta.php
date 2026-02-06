<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = (int)$_SESSION['usuario_id'];

// Actualizar el campo eliminado a 1
$query_delete = "UPDATE usuarios SET eliminado = b'1' WHERE id_usuario = $id_usuario";
if (mysqli_query($conexion, $query_delete)) {
    // Destruir sesión y redirigir
    session_destroy();
    header("Location: index.php?mensaje=cuenta_eliminada");
    exit();
} else {
    echo "Error al eliminar la cuenta. Intenta nuevamente.";
}
?>
