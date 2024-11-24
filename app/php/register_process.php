<?php
include 'db_connect.php'; // Incluir la conexión a la base de datos

// Función para validar el formato del DNI
function validarDNI($dni) {
    $patron = "/^\d{8}-[A-Z]$/";  // Patrón para 12345678-X
    return preg_match($patron, $dni);
}

// Función para validar que el nombre solo contiene letras
function validarNombre($nombre) {
    return preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre);  // Solo letras y espacios
}

// Función para validar el teléfono (9 dígitos)
function validarTelefono($telefono) {
    return preg_match("/^\d{9}$/", $telefono);  // Exactamente 9 dígitos
}

// Función para validar el email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para validar la contraseña (debe ser fuerte)
function validarPassword($password) {
    // Contraseña debe tener al menos 8 caracteres, incluir una mayúscula, un número y un carácter especial
    $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\":;'?\/.><,]).{8,}$/";
    return preg_match($pattern, $password);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiar y escapar los datos del formulario
    $dni = $_POST['DNI'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefonoa'];
    $fecha_nacimiento = $_POST['jaiotze_data'];
    $email = $_POST['email'];
    $password = $_POST['pasahitza'];

    // Estructura HTML mínima para que funcione el JavaScript
    echo '<html><head>';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '</head><body>';

    // Validar el formato del DNI antes de continuar
    if (!validarDNI($dni)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Baliozkotze-errorea',
                    text: 'NANaren formatua ez da baliozkoa. Formatua: 12345678-X.',
                    confirmButtonText: 'Barriro saiatu'
                }).then(function() {
                        window.location = '../orriak/register.php'; 
                });
              </script>";
        exit();
    }

    // Validar el nombre (solo letras)
    if (!validarNombre($nombre)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Baliozkotze-errorea',
                    text: ' Izenak letrak baino ez ditu izan behar.',
                    confirmButtonText: 'Barriro saiatu'
                }).then(function() {
                        window.location = '../orriak/register.php'; 
                });
              </script>";
        exit();
    }

    // Validar el teléfono (9 dígitos)
    if (!validarTelefono($telefono)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Baliozkotze-errorea',
                    text: 'Telefono zenbakiak 9 digitu eduki behar ditu .',
                    confirmButtonText: 'Barriro saiatu'
                }).then(function() {
                        window.location = '../orriak/register.php'; 
                });
              </script>";
        exit();
    }

    // Validar el formato del email
    if (!validarEmail($email)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Baliozkotze-errorea',
                    text: ' Posta elektronikoaren formatua ez da baliozkoa.',
                    confirmButtonText: 'Barriro saiatu'
                }).then(function() {
                        window.location = '../orriak/register.php'; 
                });
              </script>";
        exit();
    }

    // Validar que la contraseña sea fuerte
    if (!validarPassword($password)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Pasahitza ahula',
                    text: 'Pasahitzak gutxienez 8 karaktere izan behar ditu, letra maiuskula bat, zenbaki bat eta ikurren bat izan behar du.',
                    confirmButtonText: 'Barriro saiatu'
                }).then(function() {
                        window.location = '../orriak/register.php'; 
                });
              </script>";
        exit();
    }

    // **Generar el hash de la contraseña con salt**
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // **Preparar la consulta SQL usando consultas parametrizadas**
    $sql = "INSERT INTO usuarios (DNI, izen_abizenak, telefonoa, jaiotze_data, email, pasahitza) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "ssssss", $dni, $nombre, $telefono, $fecha_nacimiento, $email, $password_hash);

        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Erregistro arrakastatsua',
                        text: 'Ondo erregistratu zara!',
                        confirmButtonText: 'Saioa hasi'
                    }).then(function() {
                            window.location = '../orriak/login.php'; 
                    });
                  </script>";
        } else {
            // Verificar si el error es por duplicación del DNI (error 1062)
            if (mysqli_errno($conn) == 1062) {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Erregistro-errorea',
                            text: 'NAN-a: \"$dni\" erregistratuta dago.',
                            confirmButtonText: 'Berriro saiatu'
                        }).then(function() {
                            window.location = '../orriak/register.php'; 
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Datu-basearen errorea',
                            text: '" . mysqli_error($conn) . "',
                            confirmButtonText: 'Barriro saiatu'
                        }).then(function() {
                            window.location = '../orriak/register.php'; 
                        });
                      </script>";
            }
        }

        // Cerrar la sentencia
        mysqli_stmt_close($stmt);
    }

    echo '</body></html>';
}

mysqli_close($conn);
?>
