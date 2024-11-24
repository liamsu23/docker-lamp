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

// Preparar la consulta parametrizada para obtener la película
$sql = "SELECT * FROM pelikulak WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Vincular el parámetro
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    
    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);
    
    // Obtener el resultado
    $result = mysqli_stmt_get_result($stmt);
    
    // Verificar si se encontró la película
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
    } else {
        echo "Película no encontrada.";
        exit();
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
    <title>Filmea Ikusi - Bideoklub</title>
    <link rel="stylesheet" href="../../css/styles.css"> <!-- Enlace a tu archivo CSS -->
    <script>
        function confirmDelete() {
            return confirm('Ziur zaude film hau ezabatu nahi duzula? Ekintza hau ezin da desegin.');
        }
    </script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="user_menu.php">
                <img src="../../images/logo.png" alt="Logo Videoclub"> <!-- Logo del Videoclub -->
            </a>
        </div>
        <h1>Filmaren Informazioa</h1>
        <nav>
            <ul>
                <li><a href="/php/logout.php">Saioa Itxi</a></li>
            </ul>
        </nav>
    </header>

    <div class="hero">
        <main>
            <div class="movie-info">
                <h1><?php echo htmlspecialchars($item['izenburua']); ?></h1>
                <p>Zuzendaria: <?php echo htmlspecialchars($item['zuzendaria']); ?></p>
                <p>Estrenaldi Urtea: <?php echo htmlspecialchars($item['estrenaldi_urtea']); ?></p>
                <p>Generoa: <?php echo htmlspecialchars($item['generoa']); ?></p>
            </div>
            
            <div class="button-container">
                <a href="modify_item.php?item=<?php echo $item_id; ?>" class="btn">Aldatu</a>
                <!-- Formulario para eliminar la película con confirmación -->
                <form action="delete_item.php?item=<?php echo $item_id; ?>" method="POST" onsubmit="return confirmDelete();" style="display:inline;">
                    <button type="submit" id="item_delete_submit" class="btn">Ezabatu</button>
                </form>
            </div>
        </main>
    </div>
    
    <footer>
        <p>&copy; 2024 Bideokluba.</p>
    </footer>
</body>
</html>
