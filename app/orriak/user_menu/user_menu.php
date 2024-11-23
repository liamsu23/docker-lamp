<?php
ini_set('session.cookie_httponly', 1);
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erabiltzailearen Menua</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="user_menu.php">
                <img src="../../images/logo.png" alt="Logo Videoclub"> <!-- Logo del Videoclub -->
            </a>
        </div>
        <h2>Ongi etorri, <?php echo $_SESSION['nombre']; ?></h2>
        <nav>
            <ul>
                <li><a href="/php/logout.php">Saioa Itxi</a></li>
            </ul>
        </nav>
    </header>
    <div class="hero"> 
        <main>
            <ul class="button-list"> <!-- Añadir una clase aquí -->
                <li><a href="modify_user.php" class="button">Nire datuak aldatu</a></li>
                <li><a href="add_item.php" class="button">Film bat gehitu</a></li>
                <li><a href="items.php" class="button">Film guztiak ikusi</a></li>
                <li><a href="/php/logout.php" class="button">Saioa Itxi</a></li>
            </ul>
        </main>
    </div>
</body>
</html>
