<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Política de Seguridad de Contenido (CSP) -->
	<meta http-equiv="Content-Security-Policy" content="
	default-src 'self';
	script-src 'self' https://code.jquery.com/ https://www.gstatic.com; 
	style-src 'self';
	img-src 'self' data:;
	form-action 'self';
	">
    <title>Bideokluba</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Enlazamos el archivo CSS -->
</head>
<body>
<header>
        <div class="logo">
        <a href="../index.php">
            <img src="../images/logo.png" alt="Logo Videoclub"> <!-- Logo del Videoclub -->
        </a>        </div>
        <h1></h1>
        <nav>
            <ul>
                <li><a href="orriak/register.php">Erregistratu</a></li>
                <li><a href="orriak/login.php">Saioa Hasi</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2>Pelikularik hoberenak hemen aurkituko dituzu!</h2>
        </section>

    </main>

    <footer>
        <p>&copy; 2024 Bideokluba</p>
    </footer>
</body>
</html>
