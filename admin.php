<?php
include 'db.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

/* Cargar categorías */
$categorias = mysqli_query($conexion, "SELECT id_categoria, nombre_categoria FROM categorias");

/* --- LÓGICA DE GUARDADO (Mantenida igual) --- */
if (isset($_POST['btn_guardar'])) {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $precio = $_POST['precio'];
    $fecha  = $_POST['fecha'];
    $hora = $_POST['hora'];
    $ubicacion = mysqli_real_escape_string($conexion, $_POST['ubicacion']);
    $aforo_total = $_POST['aforo_total'];
    $aforo_disponible = $_POST['aforo_disponible'];
    $id_categoria = $_POST['id_categoria'];

    $imagen_final = "Imagenes/logo.png"; 

    if (!empty($_FILES['foto']['name'])) {
        $archivo = $_FILES['foto'];
        if ($archivo['error'] === 0) {
            if (!is_dir('Imagenes')) {
                mkdir('Imagenes', 0777, true);
            }
            $nombre_archivo = time() . "_" . basename($archivo['name']);
            $ruta_destino = "Imagenes/" . $nombre_archivo;
            if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                $imagen_final = $ruta_destino;
            }
        }
    }

    $sql = "INSERT INTO eventos 
            (titulo, precio, fecha_evento, hora_evento, ubicacion, imagen_url, aforo_total, aforo_disponible, id_categoria)
            VALUES 
            ('$titulo', '$precio', '$fecha', '$hora', '$ubicacion', '$imagen_final', '$aforo_total', '$aforo_disponible', '$id_categoria')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Evento guardado con éxito'); window.location='admin.php';</script>";
    } else {
        die("Error en la base de datos: " . mysqli_error($conexion));
    }
}

/* CARGAR EVENTOS PARA EL LISTADO */
$lista_eventos = mysqli_query($conexion, "SELECT id_evento, titulo, fecha_evento, ubicacion FROM eventos ORDER BY fecha_evento DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestión de Eventos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="panel-container">
        <h2>Añadir Nuevo Evento</h2>

        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <input type="text" name="titulo" placeholder="Título del evento" required>
            <input type="number" step="0.01" name="precio" placeholder="Precio (€)" required>
            <input type="date" name="fecha" required>
            <input type="time" name="hora" required>
            <input type="text" name="ubicacion" placeholder="Ubicación del evento" required>
            <input type="number" name="aforo_total" placeholder="Aforo total" required>
            <input type="number" name="aforo_disponible" placeholder="Aforo disponible" required>

            <select name="id_categoria" required>
                <option value="">Selecciona categoría</option>
                <?php
                if ($categorias && mysqli_num_rows($categorias) > 0) {
                    while ($cat = mysqli_fetch_assoc($categorias)) {
                        echo "<option value='{$cat['id_categoria']}'>{$cat['nombre_categoria']}</option>";
                    }
                }
                ?>
            </select>

            <div class="opciones-imagen">
                <p><strong>Imagen del evento:</strong></p>
                <label for="foto">Subir desde PC</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <button type="submit" name="btn_guardar" class="btn-principal">Publicar Evento</button>
        </form>

        <hr>

        <h2>Eventos Publicados</h2>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ev = mysqli_fetch_assoc($lista_eventos)): ?>
                <tr>
                    <td><?php echo $ev['titulo']; ?></td>
                    <td><?php echo $ev['fecha_evento']; ?></td>
                    <td>
                        <a href="eliminar.php?id=<?php echo $ev['id_evento']; ?>" 
                           class="btn-eliminar" 
                           onclick="return confirm('¿Estás seguro de eliminar este evento?');">
                           Eliminar
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>