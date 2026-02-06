<?php
include 'db.php'; // Conectamos a la BD

// lógica de búsqueda si el usuario usó el buscador
$termino = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$query = "SELECT * FROM eventos WHERE titulo LIKE '%$termino%'";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticketing - Inicio</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <img src="logo.png" alt="Logo">
        <nav>...</nav>
    </header>

    <main>
        <h1>Próximos Eventos</h1>
        
        <div class="evento-grid">
            <?php            
            // Este bucle "crea" HTML por cada fila de la base de datos
            while($evento = mysqli_fetch_assoc($resultado)) {
                ?>
                <div class="evento-tarjeta">
                    <img src="img/<?php echo $evento['imagen_url']; ?>">
                    <h3><?php echo $evento['titulo']; ?></h3>
                    <p><?php echo $evento['precio']; ?>€</p>
                    <a href="detalles.php?id=<?php echo $evento['id_evento']; ?>">Comprar</a>
                </div>
                <?php
            } 
            ?>
        </div>
    </main>
</body>
</html>