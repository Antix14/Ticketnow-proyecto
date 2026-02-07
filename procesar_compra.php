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
    $sql_evento = "SELECT aforo_disponible, precio FROM eventos WHERE id_evento = $id_evento";
    $res_evento = mysqli_query($conexion, $sql_evento);

    if (!$res_evento || mysqli_num_rows($res_evento) == 0) {
        die("Error: Evento no encontrado.");
    }

    $evento = mysqli_fetch_assoc($res_evento);

    // 4. Validar cantidad y aforo
    if ($cantidad <= 0) {
        echo "<script>alert('Cantidad inválida.'); window.location='detalles_evento.php?id=$id_evento';</script>";
        exit();
    }

    if ($cantidad > $evento['aforo_disponible']) {
        echo "<script>alert('No hay suficientes entradas disponibles.'); window.location='detalles_evento.php?id=$id_evento';</script>";
        exit();
    }

    // 5. Preparar datos para la inserción
    $precio_unitario = isset($evento['precio']) ? $evento['precio'] : 0;
    $fecha_compra = date('Y-m-d');

    // 6. Insertar ticket
    $sql_insert_ticket = "INSERT INTO tickets (id_evento, id_usuario, cantidad, precio_unitario, fecha_compra)
                          VALUES ($id_evento, $id_usuario, $cantidad, $precio_unitario, '$fecha_compra')";

    if (mysqli_query($conexion, $sql_insert_ticket)) {
        // Obtener el ID del ticket recién creado
        $id_ticket = mysqli_insert_id($conexion);

        // 7. Insertar pago asociado
        $monto_total = $precio_unitario * $cantidad;
        $sql_insert_pago = "INSERT INTO pagos (id_ticket, id_usuario, monto, metodo_pago, fecha_pago)
                            VALUES ($id_ticket, $id_usuario, $monto_total, 'simulado', '$fecha_compra')";
        mysqli_query($conexion, $sql_insert_pago);

        // 8. Actualizar aforo disponible
        $sql_update_aforo = "UPDATE eventos 
                             SET aforo_disponible = aforo_disponible - $cantidad 
                             WHERE id_evento = $id_evento";
        mysqli_query($conexion, $sql_update_aforo);

        echo "<script>alert('Compra realizada con éxito.'); window.location='perfil.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al procesar la compra: " . mysqli_error($conexion) . "'); window.location='detalles_evento.php?id=$id_evento';</script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
