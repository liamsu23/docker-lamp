<?php
// Configuración de conexión a la base de datos
$hostname = "db"; 
$username = "ISSKS";
$password = "Bideoklub1234!";
$db = "database";

// Crear la conexión
$conn = mysqli_connect($hostname, $username, $password, $db);

// Verificar la conexión
if (!$conn) {
    die("Konexioak huts egin du: " . mysqli_connect_error());
}
?>