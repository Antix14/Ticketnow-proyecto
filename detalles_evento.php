<?php
session_start();
include 'db.php';

// Obtener el ID del evento y protegerlo
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; 
    $query = "SELECT * FROM eventos WHERE id_evento = $id";
    $resultado = mysqli_query($conexion, $query);
    $evento = mysqli_fetch_assoc($resultado);

    if (!$evento) {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($evento['titulo']); ?> - Detalles</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="evento.css">
</head>
<body>
    <header>
        <h1>TicketNow</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="admin.php">Panel Admin</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <article class="detalle-evento">
            <img src="<?php echo $evento['imagen_url']; ?>" alt="<?php echo htmlspecialchars($evento['titulo']); ?>">

            <h2><?php echo htmlspecialchars($evento['titulo']); ?></h2>
            <p><strong>Fecha:</strong> <?php echo $evento['fecha_evento']; ?></p>
            <p><strong>Hora:</strong> <?php echo date('H:i', strtotime($evento['hora_evento'])); ?></p>
            <p><strong>Ubicación:</strong> <?php echo $evento['ubicacion'] ?? 'Consultar recinto'; ?></p>
            <p><strong>Aforo Disponible:</strong> <?php echo $evento['aforo_disponible']; ?> personas</p>          

            <div class="precio-destacado" style="padding: 0.5rem 2rem; font-size: 1.5rem; font-weight: bold; color: #333;">
                <?php echo number_format($evento['precio'], 2); ?>€ <small style="font-size: 0.8rem; color: #777;">/ entrada</small>
            </div>

            <form class="comprar" action="procesar_compra.php" method="POST">
                <input type="hidden" name="id_evento" value="<?php echo $evento['id_evento']; ?>">
                <label for="quantity">Cantidad:</label>
                <input type="number" id="quantity" name="cantidad" min="1" max="<?php echo $evento['aforo_disponible']; ?>" value="1">                
                <button type="submit">Comprar Entradas ahora</button>
            </form>
        </article>
    </main>

    <footer>
        <p>&copy; 2026 TicketNow Platform.</p>
    </footer>
</body>
</html>
