<?php
ini_set('session.cookie_httponly', 1);
session_start(); // Saioa hasi
include 'db_connect.php'; // Incluir la conexión a la base de datos

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiar los datos de entrada para evitar inyecciones SQL
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['pasahitza']);

    // Buscar el usuario por email
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    // Estructura HTML mínima para que funcione el JavaScript
    echo '<html><head>';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '</head><body>';

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Comparar la contraseña en texto plano
        if ($password === $row['pasahitza']) {
            // Iniciar sesión exitosa, guardar la sesión del usuario
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['nombre'] = $row['izen_abizenak'];

            // Redirigir al menú de usuario o página principal
            echo "<script>
                    window.location = '../orriak/user_menu/user_menu.php';
                  </script>";
        } else {
            // Mostrar alerta de contraseña incorrecta y redirigir a la página de inicio de sesión
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Pasahitza ez da zuzena',
                        text: 'Mesedez, egiaztatu zure pasahitza eta saiatu berriro.'
                    }).then(function() {
                        window.location = '../orriak/login.php'; 
                    });
                  </script>";
        }
    } else {
        // Mostrar alerta de usuario no encontrado y redirigir a la página de inicio de sesión
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Erabiltzailea ez da aurkitu',
                    text: 'Ez da aurkitu erabiltzailerik helbide elektroniko horrekin. Egiaztatu zure informazioa.'
                }).then(function() {
                    window.location = '../orriak/login.php'; 
                });
              </script>";
    }

    echo '</body></html>';
}

mysqli_close($conn);
?>