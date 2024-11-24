<?php
include 'db_connect.php'; // Incluir la conexión a la base de datos

// Verificar si el método de la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del usuario desde el formulario
    $id_user = intval($_POST['id_user']); // Obteniendo el ID del usuario
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $email = $_POST['email'];

    // Preparar la consulta SQL con parámetros
    $sql = "UPDATE usuarios SET DNI=?, izen_abizenak=?, telefonoa=?, jaiotze_data=?, email=? WHERE id_user=?";

    // Preparar la sentencia
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "sssssi", $dni, $nombre, $telefono, $fecha_nacimiento, $email, $id_user);

        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            // Redirigir después de la actualización exitosa
            header("Location: ../orriak/user_menu/user_menu.php?user=$id_user");
        } else {
            echo "Errorea erabiltzailea eguneratzean: " . mysqli_error($conn);
        }

        // Cerrar la sentencia
        mysqli_stmt_close($stmt);
    } else {
        echo "Errorea SQL-a prestatzeko: " . mysqli_error($conn);
    }
} else {
    echo "Metodo hori ez da onartzen";
}

// Cerrar la conexión
mysqli_close($conn);
?>
