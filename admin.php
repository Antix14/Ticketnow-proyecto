<?php

include 'db.php';

if (isset($_POST['btn_guardar'])) {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $precio = $_POST['precio'];
    $fecha  = $_POST['fecha'];
    $hora = $_POST['hora'];
    $aforo_total = $_POST['aforo_total'];
    $aforo_disponible = $_POST['aforo_disponible'];
    $id_categoria = $_POST['id_categoria'];

    // Imagen por defecto
    $imagen_final = "Imagenes/logo.png";

    // Procesar subida de imagen si se seleccionó un archivo
    if (!empty($_FILES['foto']['name'])) {
        $archivo = $_FILES['foto'];

        // Comprobar errores en la subida
        if ($archivo['error'] === 0) {
            // Validar tipo de imagen
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($archivo['type'], $tipos_permitidos)) {
                // Sanitizar nombre de archivo
                $nombre_archivo = time() . "_" . preg_replace('/[^a-zA-Z0-9\._-]/', '', $archivo['name']);
                $ruta_destino = "Imagenes/" . $nombre_archivo;

                // Intentar mover archivo al servidor
                if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                    $imagen_final = $ruta_destino;
                } else {
                    echo "<script>alert('Error al guardar la imagen en el servidor. Se usará la imagen por defecto.');</script>";
                }
            } else {
                echo "<script>alert('Tipo de archivo no permitido. Se usará la imagen por defecto.');</script>";
            }
        } else {
            echo "<script>alert('Error en la subida del archivo. Se usará la imagen por defecto.');</script>";
        }
    }

    // Insertar evento en la base de datos
    $sql = "INSERT INTO eventos (titulo, precio_total, fecha_evento, hora_evento, imagen_url, aforo_total, aforo_disponible, id_categoria) 
            VALUES ('$titulo', '$precio', '$fecha', '$hora', '$imagen_final', '$aforo_total', '$aforo_disponible', '$id_categoria')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Evento guardado con éxito'); window.location='admin.php';</script>";
    } else {
        echo "Error al guardar evento: " . mysqli_error($conexion);
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
            <input type="number" name="aforo_total" placeholder="Aforo total" required>
            <input type="number" name="aforo_disponible" placeholder="Aforo disponible" required>
            <select name="id_categoria" required>
                <option value="">Selecciona categoría</option>
                <option value="1">Concierto</option>
                <option value="2">Deporte</option>
                <option value="3">Festival</option>    
            </select>

            <div class="opciones-imagen">
                <p><strong>Imagen del evento:</strong></p>
                <label for="foto">Subir desde PC</label>
                <input type="file" id="foto" name="foto" accept="image/*">                
                <p style="text-align:center; font-weight: bold; color: #999; margin: 10px 0;">— O —</p>
            </div>

            <button type="submit" name="btn_guardar" class="btn-principal">Publicar Evento</button>
        </form>
    </div>
</body>
</html>
