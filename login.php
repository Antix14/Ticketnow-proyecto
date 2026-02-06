<?php
session_start();
include 'db.php';

$error = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = $_POST['password'];

    // Consulta solo usuarios activos (eliminado = 0)
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND eliminado = b'0'";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        // Verifica si la contraseña coincide (usando hash o texto plano según tu BD)
        if (password_verify($password, $usuario['contraseña']) || $password == $usuario['contraseña']) {
            
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            if ($usuario['rol'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "La contraseña introducida no es correcta.";
        }
    } else {
        // Aquí puede ser porque el correo no existe o porque la cuenta está eliminada
        $error = "Este correo electrónico no está registrado o la cuenta ha sido eliminada.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - TicketNow</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <h1>TicketNow</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="registro.php">Registrarse</a>
        </nav>
    </header>

    <main>
        <form class="login-form" action="login.php" method="POST">
            <h2>Acceso a tu cuenta</h2>

            <?php if($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
            
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
            
            <button type="submit">Entrar a la plataforma</button>
            
            <p>¿Aún no tienes cuenta? <a href="registro.php">Crea una aquí</a></p>
        </form>
    </main>

    <footer>
        <p>&copy; 2026 TicketNow Platform.</p>
    </footer>
</body>
</html>
