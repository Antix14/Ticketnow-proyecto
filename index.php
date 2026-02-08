<?php 
session_start();
include 'db.php';

// Capturar búsqueda
$busqueda = isset($_GET['q']) ? mysqli_real_escape_string($conexion, $_GET['q']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TicketNow - Explora Eventos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- MENÚ FIJO -->
    <header class="menu-fijo">
        <div class="logo">
            <a href="index.php">TicketNow</a>
        </div>
        <nav class="nav-principal">
            <a href="index.php">Inicio</a>
            <a href="explorar_eventos.php">Explorar Eventos</a>

            <?php if(isset($_SESSION['usuario_id'])): ?>
                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                    <a href="admin.php">Panel de Admin</a>
                <?php endif; ?>
                <a href="perfil.php">Mi perfil</a>
                <a href="logout.php">Cerrar sesión</a>
            <?php else: ?>
                <a href="login.php">Iniciar sesión</a>
                <a href="registro.php">Registrarse</a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- BUSCADOR -->
    <section class="buscador">
        <form action="index.php" method="GET">
            <input type="text" name="q" placeholder="Busca tu evento..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit">Buscar</button>
        </form>
    </section>

    <!-- GRID DE EVENTOS -->
    <main class="evento-grid">
        <?php
        $sql_eventos = "SELECT * FROM eventos 
                        WHERE titulo LIKE '%$busqueda%' 
                        ORDER BY fecha_evento ASC";
        $res_eventos = mysqli_query($conexion, $sql_eventos);

        if(mysqli_num_rows($res_eventos) > 0){
            while($evento = mysqli_fetch_assoc($res_eventos)){
                echo "
                <article class='evento-tarjeta'>
                    <img src='{$evento['imagen_url']}' alt='{$evento['titulo']}'>
                    <h3>{$evento['titulo']}</h3>
                    <p><strong>Fecha:</strong> {$evento['fecha_evento']}</p>
                    <p><strong>Hora:</strong> ".date('H:i', strtotime($evento['hora_evento']))."</p>
                    <p><strong>Precio:</strong> ".number_format($evento['precio'],2)."€</p>
                    <a href='detalles_evento.php?id={$evento['id_evento']}' class='btn'>Ver detalles</a>
                </article>";
            }
        } else {
            echo "<p style='grid-column: 1/-1; text-align:center;'>No se encontraron eventos.</p>";
        }
        ?>
    </main>
</body>
</html>
