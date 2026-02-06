<?php
session_start();
include 'db.php';

// 1. Verificar que el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// 2. Verificar que se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_evento'], $_POST['cantidad'])) {
    $id_evento = (int)$_POST['id_evento'];
    $cantidad = (int)$_POST['cantidad'];

    // 3. Obtener información del evento
    $sql_evento = "SELECT aforo_disponible, precio_total FROM eventos WHERE id_evento = $id_evento";
    $res_evento = mysqli_query($conexion, $sql_evento);

    if (!$res_evento || mysqli_num_rows($res_evento) == 0) {
        echo "<script>alert('Evento no encontrado.'); window.location='index.php';</script>";
        exit();
    }

    $evento = mysqli_fetch_assoc($res_evento);

    // 4. Validar que hay suficiente aforo
    if ($cantidad <= 0) {
        echo "<script>alert('Cantidad inválida.'); window.location='detalles_evento.php?id=$id_evento';</script>";
        exit();
    }

    if ($cantidad > $evento['aforo_disponible']) {
        echo "<script>alert('No hay suficientes entradas disponibles.'); window.location='detalles_evento.php?id=$id_evento';</script>";
        exit();
    }

    // 5. Insertar ticket
    $precio_unitario = $evento['precio_total'];
    $fecha_compra = date('Y-m-d');

    $sql_insert = "INSERT INTO tickets (id_evento, id_usuario, cantidad, precio_unitario, fecha_compra)
                   VALUES ($id_evento, $id_usuario, $cantidad, $precio_unitario, '$fecha_compra')";

    if (mysqli_query($conexion, $sql_insert)) {
        // 6. Actualizar aforo disponible
        $sql_update = "UPDATE eventos 
                       SET aforo_disponible = aforo_disponible - $cantidad 
                       WHERE id_evento = $id_evento";
        mysqli_query($conexion, $sql_update);

        echo "<script>alert('Compra realizada con éxito.'); window.location='perfil.php';</script>";
    } else {
        echo "<script>alert('Error al procesar la compra. Intenta de nuevo.'); window.location='detalles_evento.php?id=$id_evento';</script>";
    }
} else {
    // Si no se envió el formulario
    header("Location: index.php");
    exit();
}
?>
