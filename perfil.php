<?php
session_start();
include 'db.php';

// 1. Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = (int)$_SESSION['usuario_id'];

// 2. Obtener datos del usuario
$query_user = "SELECT nombre, email FROM usuarios WHERE id_usuario = $id_usuario";
$res_user = mysqli_query($conexion, $query_user);
$datos_usuario = mysqli_fetch_assoc($res_user);

// 3. Obtener tickets del usuario, con precio y ordenados por fecha
$query_tickets = "
    SELECT e.titulo, e.fecha_evento, t.cantidad, t.precio_unitario
    FROM tickets t
    JOIN eventos e ON t.id_evento = e.id_evento
    WHERE t.id_usuario = $id_usuario
    ORDER BY e.fecha_evento ASC
";
$res_tickets = mysqli_query($conexion, $query_tickets);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - TicketNow</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="perfil.css">
</head>
<body>
    <header>
        <h1>TicketNow</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <main>
        <h2>Mi Perfil</h2>
        
        <section class="user-card">
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($datos_usuario['nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($datos_usuario['email']); ?></p>
        </section>

        <h3>Mis Entradas Adquiridas</h3>
        
        <ul class="tickets-list">
            <?php 
            if (mysqli_num_rows($res_tickets) > 0) {
                while($ticket = mysqli_fetch_assoc($res_tickets)) {
                    echo "
                    <li class='ticket-item'>
                        <div class='ticket-info'>
                            <span class='ticket-title'>" . htmlspecialchars($ticket['titulo']) . "</span>
                            <span class='ticket-date'>Fecha del evento: " . $ticket['fecha_evento'] . "</span>
                            <span class='ticket-price'>Precio: " . number_format($ticket['precio_unitario'], 2) . "€</span>
                        </div>
                        <div class='ticket-qty'>
                            " . $ticket['cantidad'] . " entradas
                        </div>
                    </li>";
                }
            } else {
                echo "<li class='no-tickets'>Aún no has comprado entradas para ningún evento.</li>";
            }
            ?>
        </ul>
    </main>

    <footer>
        <p>&copy; 2026 TicketNow Platform.</p>
    </footer>
</body>
</html>
