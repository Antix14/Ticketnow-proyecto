<?php
include 'db.php';
session_start();

// Seguridad: solo el admin puede borrar
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Aseguramos que sea un número

    // 1. Primero borramos los pagos asociados a los tickets de este evento
    mysqli_query($conexion, "DELETE FROM pagos WHERE id_ticket IN (SELECT id_ticket FROM tickets WHERE id_evento = $id)");

    // 2. Borramos los tickets asociados al evento
    mysqli_query($conexion, "DELETE FROM tickets WHERE id_evento = $id");

    // 3. Finalmente borramos el evento
    mysqli_query($conexion, "DELETE FROM eventos WHERE id_evento = $id");
}

header("Location: admin.php");
exit();
?>