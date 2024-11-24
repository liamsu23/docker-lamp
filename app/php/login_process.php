<?php
ini_set('session.cookie_httponly', 1); 
session_start(); // Saioa hasi
include 'db_connect.php'; // Incluir la conexión a la base de datos

$timeout_duration = 1800; // 30 minutos

if (isset($_SESSION['last_activity']) && 
    (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
$_SESSION['last_activity'] = time();

// Configuración
$time_limit = "15 MINUTE"; // Tiempo límite para contar intentos fallidos
$max_attempts = 5;         // Número máximo de intentos fallidos permitidos

// Procesar el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiar los datos de entrada para evitar inyecciones SQL
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['pasahitza']);
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Verificar intentos fallidos recientes
    $sql_attempts = "SELECT COUNT(*) AS failed_attempts 
                     FROM login_attempts 
                     WHERE email='$email' AND attempt_time > NOW() - INTERVAL $time_limit";
    $result_attempts = mysqli_query($conn, $sql_attempts);
    $row_attempts = mysqli_fetch_assoc($result_attempts);

    if ($row_attempts['failed_attempts'] >= $max_attempts) {
        $log_message = "[" . date("Y-m-d H:i:s") . "] Huts egindako saiakera gehiegi antzeman dira: 
        Email: $email, IP: $ip_address" . PHP_EOL;
        file_put_contents("login_attempts.log", $log_message, FILE_APPEND);
        
        // Mostrar mensaje de bloqueo
        echo '<html><head>';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '</head><body>';
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Huts egindako saiakera gehiegi',
                text: 'Berriro saiatu arte itxaron ezazu mesedez.'
            }).then(function() {
                window.location = '../orriak/login.php'; 
            });
        </script>";
        echo '</body></html>';
        // Detener la ejecución del script
        exit;
    }

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
            // Reiniciar intentos fallidos al inicio de sesión exitoso
            $sql_reset_attempts = "DELETE FROM login_attempts WHERE email='$email'";
            mysqli_query($conn, $sql_reset_attempts);

            // Iniciar sesión exitosa, guardar la sesión del usuario
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['nombre'] = $row['izen_abizenak'];

            // Registrar inicio de sesión exitoso en la base de datos
            $sql_log_success = "INSERT INTO login_history (email, ip_address, status) 
                                VALUES ('$email', '$ip_address', 'success')";
            mysqli_query($conn, $sql_log_success);

            // Redirigir al menú de usuario o página principal
            echo "<script>
                    window.location = '../orriak/user_menu/user_menu.php';
                  </script>";
        } else {
            // Registrar intento fallido
            $sql_log_attempt = "INSERT INTO login_attempts (email, ip_address) VALUES ('$email', '$ip_address')";
            mysqli_query($conn, $sql_log_attempt);

            // Registrar el fallo en el historial de login
            $sql_log_failure = "INSERT INTO login_history (email, ip_address, status) 
                                VALUES ('$email', '$ip_address', 'failed')";
            mysqli_query($conn, $sql_log_failure);

            // Mostrar alerta de contraseña incorrecta
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
        // Registrar intento fallido para usuario inexistente
        $sql_log_attempt = "INSERT INTO login_attempts (email, ip_address) VALUES ('$email', '$ip_address')";
        mysqli_query($conn, $sql_log_attempt);

        // Registrar el fallo en el historial de login
        $sql_log_failure = "INSERT INTO login_history (email, ip_address, status) 
                            VALUES ('$email', '$ip_address', 'failed')";
        mysqli_query($conn, $sql_log_failure);

        // Mostrar alerta de usuario no encontrado
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
