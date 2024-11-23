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
                <h1><?php echo $item['izenburua']; ?></h>
                <p>Zuzendaria: <?php echo $item['zuzendaria']; ?></p>
                <p>Estrenaldi Urtea: <?php echo $item['estrenaldi_urtea']; ?></p>
                <p>Generoa: <?php echo $item['generoa']; ?></p>
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
