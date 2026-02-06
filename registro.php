<?php
include 'db.php';

$mensaje = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['name']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = $_POST['password'];

    $password_encriptada = password_hash($password, PASSWORD_BCRYPT);

    $comprobar = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email = '$email'");
    
    if (mysqli_num_rows($comprobar) > 0) {
        $mensaje = "<p style='color:red;'>El correo ya está registrado.</p>";
    } else {
        $sql = "INSERT INTO usuarios (nombre, email, contraseña, rol) 
                VALUES ('$nombre', '$email', '$password_encriptada', 'cliente')";
        
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "<p style='color:green;'>¡Cuenta creada con éxito! <a href='login.php' style='font-weight:bold; color:#065f46;'>Inicia sesión aquí</a></p>";
        } else {
            $mensaje = "<p style='color:red;'>Error al crear la cuenta.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - TicketNow</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="registro.css">
</head>
<body>
    <header>
        <h1>TicketNow</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="login.php">Iniciar Sesión</a>
        </nav>
    </header>

    <main>
        <form class="registro-form" action="registro.php" method="POST">
            <h2>Crear nueva cuenta</h2>

            <?php echo $mensaje; ?>

            <label for="name">Nombre Completo</label>
            <input type="text" id="name" name="name" placeholder="Tu nombre" required>

            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required>

            <button type="submit">Registrarme ahora</button>
            
            <p>¿Ya eres miembro? <a href="login.php">Inicia Sesión</a></p>
        </form>
    </main>

    <footer>
        <p>&copy; 2026 TicketNow Platform.</p>
    </footer>
</body>
</html>