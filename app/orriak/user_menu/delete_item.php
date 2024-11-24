<?php
ini_set('session.cookie_httponly', 1);
session_start();
include '../../php/db_connect.php'; 

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit();
}

// Validar y obtener el parámetro 'item' de la URL
if (!isset($_GET['item']) || !is_numeric($_GET['item'])) {
    echo "ID de película no válido.";
    exit();
}

$item_id = (int)$_GET['item']; // Convertir a entero para mayor seguridad

// Preparar la consulta para eliminar la película
$sql = "DELETE FROM pelikulak WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Vincular el parámetro y ejecutar la consulta
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Redirigir al usuario de nuevo a la lista de películas si la eliminación fue exitosa
        header("Location: items.php");
        exit();
    } else {
        echo "Error al eliminar la película: " . mysqli_stmt_error($stmt);
    }

    // Cerrar el statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error al preparar la consulta: " . mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
