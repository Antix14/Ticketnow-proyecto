<?php 
include 'db.php'; 

// Lógica para añadir evento
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear'])) {
    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $fecha  = $_POST['fecha'];
    
    $sql = "INSERT INTO eventos (titulo, precio, fecha_evento) VALUES ('$titulo', '$precio', '$fecha')";
    mysqli_query($conexion, $sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Admin - Gestión de Eventos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1>Panel de Administración</h1>
    
    <form action="admin.php" method="POST" style="margin-bottom: 50px;">
        <h3>Crear Nuevo Evento</h3>
        <input type="text" name="titulo" placeholder="Nombre del evento" required>
        <input type="number" step="0.01" name="precio" placeholder="Precio (€)" required>
        <input type="date" name="fecha" required>
        <button type="submit" name="crear">Publicar Evento</button>
    </form>

    <h3>Eventos en Base de Datos</h3>
    <table border="1" width="100%">
        <tr>
            <th>Título</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php
        $res = mysqli_query($conexion, "SELECT * FROM eventos");
        while($f = mysqli_fetch_assoc($res)) {
            echo "<tr>
                    <td>{$f['titulo']}</td>
                    <td>{$f['precio']}€</td>
                    <td><a href='eliminar.php?id={$f['id_evento']}'>Eliminar</a></td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>