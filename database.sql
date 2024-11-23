-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 16-09-2020 a las 16:37:17
-- Versión del servidor: 10.5.5-MariaDB-1:10.5.5+maria~focal
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,  -- Asegúrate de que esté en la definición
  `DNI` varchar(10) NOT NULL,
  `izen_abizenak` text NOT NULL,
  `telefonoa` int(9) NOT NULL,
  `jaiotze_data` date NOT NULL,
  `email` text NOT NULL,
  `pasahitza` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`),  -- Define la clave primaria aquí
  UNIQUE KEY `DNI` (`DNI`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_user`, `DNI`, `izen_abizenak`, `telefonoa`, `jaiotze_data`, `email`, `pasahitza`) VALUES
(1, '12345678-A', 'Proba Bat', 600123456, '1990-05-15', 'proba.bat@example.com', 'password123'),
(2, '87654321-B', 'Proba Bi ', 610987654, '1988-09-22', 'proba.2@example.com', 'securePass456'),
(3, '56781234-C', 'Lamine Yamal', 620654321, '1992-12-10', 'lamine.FCB@example.com', 'pass789xyz'),
(4, '43218765-D', 'Pepe Palotes', 630321987, '1995-03-05', 'pepe1234@example.com', 'pepe1234'),
(5, '34567812-E', 'Miguel Torres', 640789123, '1991-07-30', 'MykeTowers.23@example.com', 'EasyMoneyBaby23');

COMMIT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelikulak`
--

CREATE TABLE `pelikulak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `izenburua` varchar(255) NOT NULL,
  `zuzendaria` varchar(255) DEFAULT NULL,
  `estrenaldi_urtea` year DEFAULT NULL,
  `generoa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)  -- Define la clave primaria aquí
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pelikulak`
--

INSERT INTO `pelikulak` (`id`, `izenburua`, `zuzendaria`, `estrenaldi_urtea`, `generoa`) VALUES
(1, 'Inception', 'Christopher Nolan', 2010, 'Zientzia fikzioa'),
(2, 'El Padrino', 'Francis Ford Coppola', 1972, 'Krimen'),
(3, 'Pulp Fiction', 'Quentin Tarantino', 1994, 'Krimen'),
(4, 'La lista de Schindler', 'Steven Spielberg', 1993, 'Drama'),
(5, 'El caballero oscuro', 'Christopher Nolan', 2008, 'Akzioa'),
(6, 'Forrest Gump', 'Robert Zemeckis', 1994, 'Drama'),
(7, 'El señor de los anillos: La comunidad del anillo', 'Peter Jackson', 2001, 'Fantasia'),
(8, 'Matrix', 'Lana Wachowski, Lilly Wachowski', 1999, 'Zientzia fikzioa'),
(9, 'Gladiator', 'Ridley Scott', 2000, 'Akzioa'),
(10, 'Titanic', 'James Cameron', 1997, 'Erromantze'),
(11, 'Star Wars: Episodio IV - Una nueva esperanza', 'George Lucas', 1977, 'Zientzia fikzioa'),
(12, 'Jurassic Park', 'Steven Spielberg', 1993, 'Zientzia fikzioa'),
(13, 'Regreso al futuro', 'Robert Zemeckis', 1985, 'Zientzia fikzioa'),
(14, 'El silencio de los inocentes', 'Jonathan Demme', 1991, 'Suspense'),
(15, 'El gran Lebowski', 'Joel Coen, Ethan Coen', 1998, 'Komedia'),
(16, 'Toy Story', 'John Lasseter', 1995, 'Animazioa'),
(17, 'Avatar', 'James Cameron', 2009, 'Zientzia fikzioa'),
(18, 'Alien: El octavo pasajero', 'Ridley Scott', 1979, 'Beldurra'),
(19, 'Los infiltrados', 'Martin Scorsese', 2006, 'Krimen'),
(20, 'Interstellar', 'Christopher Nolan', 2014, 'Zientzia fikzioa');

COMMIT;

CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    ip_address VARCHAR(45),
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
