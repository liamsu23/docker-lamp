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
    $email = $_POST['email'];
    $password = $_POST['pasahitza'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Verificar intentos fallidos recientes
    $sql_attempts = "SELECT COUNT(*) AS failed_attempts 
                     FROM login_attempts 
                     WHERE email=? AND attempt_time > NOW() - INTERVAL $time_limit";
    $stmt = mysqli_prepare($conn, $sql_attempts);
    mysqli_stmt_bind_param($stmt, "s", $email); // 's' indica que es un parámetro de tipo string
    mysqli_stmt_execute($stmt);
    $result_attempts = mysqli_stmt_get_result($stmt);
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

    // Buscar el usuario por email usando una consulta parametrizada
    $sql = "SELECT * FROM usuarios WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email); // 's' indica que es un parámetro de tipo string
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Estructura HTML mínima para que funcione el JavaScript
    echo '<html><head>';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '</head><body>';

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Comparar la contraseña en texto plano con el hash almacenado
        if (password_verify($password, $row['pasahitza'])) {
            // Reiniciar intentos fallidos al inicio de sesión exitoso
            $sql_reset_attempts = "DELETE FROM login_attempts WHERE email=?";
            $stmt_reset = mysqli_prepare($conn, $sql_reset_attempts);
            mysqli_stmt_bind_param($stmt_reset, "s", $email); 
            mysqli_stmt_execute($stmt_reset);

            // Iniciar sesión exitosa, guardar la sesión del usuario
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['nombre'] = $row['izen_abizenak'];

            // Registrar inicio de sesión exitoso en la base de datos
            $sql_log_success = "INSERT INTO login_history (email, ip_address, status) 
                                VALUES (?, ?, 'success')";
            $stmt_log_success = mysqli_prepare($conn, $sql_log_success);
            mysqli_stmt_bind_param($stmt_log_success, "ss", $email, $ip_address);
            mysqli_stmt_execute($stmt_log_success);

            // Redirigir al menú de usuario o página principal
            echo "<script>
                    window.location = '../orriak/user_menu/user_menu.php';
                  </script>";
        } else {
            // Registrar intento fallido
            $sql_log_attempt = "INSERT INTO login_attempts (email, ip_address) VALUES (?, ?)";
            $stmt_log_attempt = mysqli_prepare($conn, $sql_log_attempt);
            mysqli_stmt_bind_param($stmt_log_attempt, "ss", $email, $ip_address);
            mysqli_stmt_execute($stmt_log_attempt);

            // Registrar el fallo en el historial de login
            $sql_log_failure = "INSERT INTO login_history (email, ip_address, status) 
                                VALUES (?, ?, 'failed')";
            $stmt_log_failure = mysqli_prepare($conn, $sql_log_failure);
            mysqli_stmt_bind_param($stmt_log_failure, "ss", $email, $ip_address);
            mysqli_stmt_execute($stmt_log_failure);

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
        $sql_log_attempt = "INSERT INTO login_attempts (email, ip_address) VALUES (?, ?)";
        $stmt_log_attempt = mysqli_prepare($conn, $sql_log_attempt);
        mysqli_stmt_bind_param($stmt_log_attempt, "ss", $email, $ip_address);
        mysqli_stmt_execute($stmt_log_attempt);

        // Registrar el fallo en el historial de login
        $sql_log_failure = "INSERT INTO login_history (email, ip_address, status) 
                            VALUES (?, ?, 'failed')";
        $stmt_log_failure = mysqli_prepare($conn, $sql_log_failure);
        mysqli_stmt_bind_param($stmt_log_failure, "ss", $email, $ip_address);
        mysqli_stmt_execute($stmt_log_failure);

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
