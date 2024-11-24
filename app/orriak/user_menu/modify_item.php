<?php
ini_set('session.cookie_httponly', 1);
session_start();
include '../../php/db_connect.php'; 

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit();
}

// Verificar si se recibe el parámetro `item`
if (!isset($_GET['item']) || empty($_GET['item'])) {
    echo "Elemento no especificado.";
    exit();
}

// Limpiar el parámetro recibido
$item_id = intval($_GET['item']);

// Preparar la consulta para seleccionar la película
$sql = "SELECT * FROM pelikulak WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Enlazar el parámetro
    mysqli_stmt_bind_param($stmt, "i", $item_id);

    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);

    // Obtener los resultados
    $result = mysqli_stmt_get_result($stmt);

    // Validar si se encontró el elemento
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
    } else {
        echo "Elemento no encontrado.";
        exit();
    }

    // Liberar recursos del statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error al preparar la consulta: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmea Aldatu - Bideoklub</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="user_menu.php">
                <img src="../../images/logo.png" alt="Logo Videoclub"> <!-- Logo del Videoclub -->
            </a>
        </div>
        <h1>Filmea Aldatu</h1>
        <nav>
            <ul>
                <li><a href="/php/logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <div class="hero">
    <main>
        <form id="item_modify_form" action="../../php/modify_item_process.php?item=<?php echo $item_id; ?>" method="POST">
            <label for="izenburua">Izenburua:</label>
            <input type="text" id="izenburua" name="izenburua" value="<?php echo htmlspecialchars($item['izenburua']); ?>" required>

            <label for="zuzendaria">Zuzendaria:</label>
            <input type="text" id="zuzendaria" name="zuzendaria" value="<?php echo htmlspecialchars($item['zuzendaria']); ?>" required>

            <label for="estrenaldi_urtea">Urtea:</label>
            <input type="text" id="estrenaldi_urtea" name="estrenaldi_urtea" value="<?php echo htmlspecialchars($item['estrenaldi_urtea']); ?>" required>

            <label for="generoa">Generoa:</label>
            <input type="text" id="generoa" name="generoa" value="<?php echo htmlspecialchars($item['generoa']); ?>" required>

            <button type="submit" id="item_modify_submit">Pelikula Aldatu</button>
        </form>
    </main>
    </div>
    

    <footer>
        <p>&copy; 2024 Bideokluba.</p>
    </footer>
</body>
</html>
