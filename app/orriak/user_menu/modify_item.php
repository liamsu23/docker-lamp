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
$query = mysqli_query($conn, "SELECT * FROM pelikulak WHERE id = '$item_id'");

$item = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmea Aldatu - Bideoklub</title>
    <link rel="stylesheet" href="../../css/styles.css"> <
</head>
<body>
    <header>
        <div class="logo">
            <a href="user_menu.php">
                <img src="../../images/logo.png" alt="Logo Videoclub"> <!-- Logo del Videoclub -->
            </a>
        </div>
        <h1>Filmea ALdatu</h1>
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
            <input type="text" id="izenburua" name="izenburua" value="<?php echo $item['izenburua']; ?>" required>

            <label for="zuzendaria">Zuzendaria:</label>
            <input type="text" id="zuzendaria" name="zuzendaria" value="<?php echo $item['zuzendaria']; ?>" required>

            <label for="estrenaldi_urtea">Urtea:</label>
            <input type="text" id="estrenaldi_urtea" name="estrenaldi_urtea" value="<?php echo $item['estrenaldi_urtea']; ?>" required>

            <label for="generoa">Generoa:</label>
            <input type="text" id="generoa" name="generoa" value="<?php echo $item['generoa']; ?>" required>

            <button type="submit" id="item_modify_submit">Pelikula Aldatu</button>
        </form>
    </main>
    </div>
    

    <footer>
        <p>&copy; 2024 Bideokluba.</p>
    </footer>
</body>
</html>
