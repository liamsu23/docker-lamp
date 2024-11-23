<?php
ini_set('session.cookie_httponly', 1);
session_start();

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al index
header("Location: /index.php");
exit();
?>
