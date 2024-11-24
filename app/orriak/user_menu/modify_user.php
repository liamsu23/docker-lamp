<?php
ini_set('session.cookie_httponly', 1);
session_start();
include '../../php/db_connect.php'; 

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit();
}

// Obtener el ID del usuario directamente desde la sesión
$id_user = $_SESSION['user_id'];

// Preparar la consulta parametrizada para obtener los datos del usuario
$sql = "SELECT * FROM usuarios WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Vincular el parámetro
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    
    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);
    
    // Obtener los resultados
    $result = mysqli_stmt_get_result($stmt);
    
    // Comprobar si se encontró el usuario
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $row = null;
    }

    // Cerrar el statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error al preparar la consulta: " . mysqli_error($conn);
    exit();
}

// Cerrar la conexión
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erabiltzailea aldatu</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="user_menu.php">
                <img src="../../images/logo.png" alt="Logo Videoclub">
            </a>
        </div>
        <h2>Erabiltzailea aldatu</h2>
        <nav>
            <ul>
                <li><a href="/php/logout.php">Saioa Itxi</a></li>
            </ul>
        </nav>
    </header>

    <div class="hero"> 
        <main>
            <?php if ($row): ?>
                <form method="post" action="../../php/modify_user_process.php" class="modify-user-form">
                    <input type="hidden" name="id_user" value="<?= htmlspecialchars($row['id_user']); ?>"> <!-- Para pasar el ID del usuario -->
                    <div>
                        <label for="dni">DNI:</label>
                        <input type="text" name="dni" value="<?= htmlspecialchars($row['DNI']); ?>" required>
                    </div>
                    <div>
                        <label for="nombre">Izena:</label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($row['izen_abizenak']); ?>" required>
                    </div>
                    <div>
                        <label for="telefono">Telefonoa:</label>
                        <input type="text" name="telefono" value="<?= htmlspecialchars($row['telefonoa']); ?>" required>
                    </div>
                    <div>
                        <label for="fecha_nacimiento">Jaiotze Data:</label>
                        <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($row['jaiotze_data']); ?>" required>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required>
                    </div>
                    <button type="submit">Eguneratu</button>
                </form>
            <?php else: ?>
                <p>Erabiltzailea ez da aurkitu.</p>
            <?php endif; ?>
        </main>
    </div>

    <footer>
        <p>&copy; 2024 Bideokluba</p>
    </footer>
</body>
</html>
