<?php 
include 'db.php'; 
$busqueda = isset($_GET['q']) ? mysqli_real_escape_string($conexion, $_GET['q']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing - Inicio</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="index.css"> 
</head>
<body>
    <header class="buscador">
        <form action="index.php" method="GET">
            <input type="text" name="q" placeholder="Busca tu evento..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit">Buscar</button>
        </form>
    </header>

    <main class="evento-grid">
        <?php
        $sql = "SELECT * FROM eventos WHERE titulo LIKE '%$busqueda%' ORDER BY fecha_evento ASC";
        $resultado = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($resultado) > 0) {
            while($evento = mysqli_fetch_assoc($resultado)) {
                echo "
                <article class='evento-tarjeta'>
                    <img src='{$evento['imagen_url']}' alt='{$evento['titulo']}'>
                    <h3>{$evento['titulo']}</h3>
                    <p><strong>Fecha:</strong> {$evento['fecha_evento']}</p>
                    <p><strong>Precio:</strong> {$evento['precio_total']}€</p>
                    <a href='detalles_evento.php?id={$evento['id_evento']}' class='btn'>
                    Ver detalles
                    </a>
                </article>";
            }
        } else {
            echo "<p style='grid-column: 1/-1; text-align: center;'>No se encontraron eventos.</p>";
        }
        ?>
    </main>
</body>
</html>