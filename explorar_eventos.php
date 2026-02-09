<?php
session_start();
include 'db.php';

// Obtener categorías para el menú de filtros
$sql_categorias = "SELECT * FROM categorias ORDER BY nombre_categoria";
$res_categorias = mysqli_query($conexion, $sql_categorias);

// Categoría seleccionada por URL
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TicketNow - Explorar por Categoría</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="explorar.css">
</head>
<body>

<header class="menu-fijo">
    <div class="logo">
        <a href="index.php">TicketNow</a>
    </div>
    <nav class="nav-principal">
        <a href="index.php">Inicio</a>
        <a href="explorar_eventos.php" class="activo">Explorar Eventos</a>

        <?php if(isset($_SESSION['usuario_id'])): ?>
            <a href="perfil.php">Mi perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Registrarse</a>
        <?php endif; ?>
    </nav>
</header>

<section class="seccion-explorar">
    <div class="contenedor-categorias">
        <a href="explorar_eventos.php" class="cat-btn <?php echo ($categoria_id === 0) ? 'activa' : ''; ?>">
            Todas
        </a>
        <?php while($cat = mysqli_fetch_assoc($res_categorias)): ?>
            <a href="explorar_eventos.php?categoria=<?php echo $cat['id_categoria']; ?>" 
               class="cat-btn <?php echo ($categoria_id === (int)$cat['id_categoria']) ? 'activa' : ''; ?>">
                <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
            </a>
        <?php endwhile; ?>
    </div>
</section>

<main class="evento-grid">
    <?php
    $sql_eventos = "SELECT * FROM eventos";
    if ($categoria_id > 0) {
        $sql_eventos .= " WHERE id_categoria = $categoria_id";
    }
    $sql_eventos .= " ORDER BY fecha_evento ASC";
    
    $res_eventos = mysqli_query($conexion, $sql_eventos);

    if(mysqli_num_rows($res_eventos) > 0):
        while($evento = mysqli_fetch_assoc($res_eventos)):
    ?>
        <article class="evento-tarjeta">
            <img src="<?php echo $evento['imagen_url']; ?>" alt="<?php echo htmlspecialchars($evento['titulo']); ?>">
            <div class="tarjeta-info">
                <h3><?php echo htmlspecialchars($evento['titulo']); ?></h3>
                <p><strong>Fecha:</strong> <?php echo $evento['fecha_evento']; ?></p>
                <p><strong>Hora:</strong> <?php echo date('H:i', strtotime($evento['hora_evento'])); ?></p>
                <p><strong>Precio:</strong> <?php echo number_format($evento['precio'], 2); ?>€</p>
            </div>
            <a href="detalles_evento.php?id=<?php echo $evento['id_evento']; ?>" class="btn">
                Ver detalles
            </a>
        </article>
    <?php 
        endwhile;
    else:
        echo "<p style='grid-column:1/-1; text-align:center;'>No hay eventos en esta categoría.</p>";
    endif;
    ?>
</main>

<footer>
    <p>&copy; 2026 TicketNow Platform.</p>
</footer>

</body>
</html>