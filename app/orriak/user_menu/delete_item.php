<?php
ini_set('session.cookie_httponly', 1);
session_start();
include '../../php/db_connect.php'; 

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit();
}

$item_id = $_GET['item'];
$query = mysqli_query($conn, "DELETE FROM pelikulak WHERE id = '$item_id'");

if ($query) {
    //echo "Película eliminada con éxito.";
    header("Location: items.php");
} else {
    echo "Error al eliminar la película.";
}
?>

<a href="items.php">Filmeak Ikustera itzuli</a>
