<?php
include 'db.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conexion, "DELETE FROM eventos WHERE id_evento = $id");
}
header("Location: admin.php");
?>