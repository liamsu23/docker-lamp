<?php
ini_set('session.cookie_httponly', 1);
session_start();
include 'db_connect.php'; // Incluir la conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit();
}

// Obtener el ID del ítem (película) desde la URL
$item_id = $_GET['item'];

// Estructura HTML mínima para que funcione el JavaScript
echo '<html><head>';
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
echo '</head><body>';

// Verificar si se recibió el formulario por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $titulo = $_POST['izenburua'];
    $director = $_POST['zuzendaria'];
    $anio = $_POST['estrenaldi_urtea'];
    $genero = $_POST['generoa'];

    // Validar los datos (opcional, pero recomendable)
    if (empty($titulo) || empty($director) || empty($anio) || empty($genero)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Eremu guztiak derrigorrezkoak dira.'
                }).then(function() {
                    window.history.back(); // Regresar a la página anterior
                });
              </script>";
        exit();
    }

    // Preparar la consulta para actualizar la película
    $sql = "UPDATE pelikulak 
            SET izenburua=?, zuzendaria=?, estrenaldi_urtea=?, generoa=? 
            WHERE id=?";

    // Preparar la sentencia
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "ssssi", $titulo, $director, $anio, $genero, $item_id);

        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Arrakasta',
                        text: 'Pelikula arrakastaz aldatua.',
                    }).then(function() {
                        window.location.href = '../orriak/user_menu/show_item.php?item=$item_id'; // Redirigir después de cerrar el pop-up
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Errorea pelikula aldatzean.'
                    }).then(function() {
                        window.history.back(); // Regresar a la página anterior
                    });
                  </script>";
        }

        // Cerrar la sentencia
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Errorea en la preparación de la consulta.'
                }).then(function() {
                    window.history.back(); // Regresar a la página anterior
                });
              </script>";
    }
}

// Cerrar la conexión
mysqli_close($conn);

echo '</body></html>'; // Cerrar la estructura HTML
?>
