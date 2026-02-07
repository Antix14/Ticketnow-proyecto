<?php
include 'db.php';

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

/* Cargar categorías */
$categorias = mysqli_query($conexion, "SELECT id_categoria, nombre_categoria FROM categorias");

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
            // Asegurarnos de que la carpeta existe
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

    // Consulta con nombres de columna revisados
    $sql = "INSERT INTO eventos 
            (titulo, precio, fecha_evento, hora_evento, ubicacion, imagen_url, aforo_total, aforo_disponible, id_categoria)
            VALUES 
            ('$titulo', '$precio', '$fecha', '$hora', '$ubicacion', '$imagen_final', '$aforo_total', '$aforo_disponible', '$id_categoria')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Evento guardado con éxito'); window.location='admin.php';</script>";
    } else {
        // Esto nos dirá el error exacto si la tabla está mal
        die("Error en la base de datos: " . mysqli_error($conexion));
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Añadir Evento</title>
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
            <!-- UBICACIÓN -->
            <input type="text" name="ubicacion" placeholder="Ubicación del evento" required>
            <input type="number" name="aforo_total" placeholder="Aforo total" required>
            <input type="number" name="aforo_disponible" placeholder="Aforo disponible" required>

            <!-- SELECT DE CATEGORÍAS DESDE BD -->
            <select name="id_categoria" required>
                <option value="">Selecciona categoría</option>

                <?php
                if ($categorias && mysqli_num_rows($categorias) > 0) {
                    while ($cat = mysqli_fetch_assoc($categorias)) {
                        echo "<option value='{$cat['id_categoria']}'>
                                {$cat['nombre_categoria']}
                              </option>";
                    }
                } else {
                    echo "<option value=''>No hay categorías creadas</option>";
                }
                ?>
            </select>

            <div class="opciones-imagen">
                <p><strong>Imagen del evento:</strong></p>
                <label for="foto">Subir desde PC</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <button type="submit" name="btn_guardar" class="btn-principal">
                Publicar Evento
            </button>
        </form>
    </div>
</body>
</html>
