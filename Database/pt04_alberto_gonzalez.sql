-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-10-2024 a las 19:59:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pt04_alberto_gonzalez`
--
CREATE DATABASE IF NOT EXISTS `pt04_alberto_gonzalez` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pt04_alberto_gonzalez`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `ID` int(11) NOT NULL,
  `titol` text NOT NULL,
  `cos` text NOT NULL,
  `usuari_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `articles`
--

INSERT INTO `articles` (`ID`, `titol`, `cos`, `usuari_id`) VALUES
(1, 'Torre d\'arqueres', 'La torre d\'arqueres és essencial per defensar la teva aldea dels atacs enemics.', 4),
(2, 'Barbarians', 'Els barbarians són una de les tropes més potents per fer atacs ràpids i sorpresius.', 4),
(3, 'Camp de Construcció', 'El camp de construcció et permet millorar les teves defenses i tropes més ràpidament.', 4),
(4, 'Estratègia de guerra', 'Desenvolupa una bona estratègia per guanyar les batalles i superar els teus rivals.', 4),
(5, 'Tropes de nivell 3', 'Les tropes de nivell 3 ofereixen millores significatives en el combat i en la defensa.', 4),
(6, 'Actualitzacions de joc', 'Manten-te informat sobre les últimes actualitzacions per treure el màxim profit del joc.', 4),
(7, 'Eixample d\'aldes', 'Aprofita l\'eixample d\'aldes per maximitzar els recursos i la defensa de la teva aldea.', 4),
(8, 'Construccions ràpides', 'Aprèn a construir i millorar les estructures de manera ràpida per defensar millor.', 4),
(9, 'Tàctiques de defensa', 'Utilitza tàctiques de defensa efectives per protegir el teu poble d\'envasions rivals.', 4),
(10, 'Clan d\'elit', 'Uneix-te a un clan d\'elit per millorar les teves habilitats i col·laborar amb altres jugadors.', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaris`
--

DROP TABLE IF EXISTS `usuaris`;
CREATE TABLE `usuaris` (
  `id` int(11) NOT NULL,
  `usuari` varchar(100) NOT NULL,
  `contrasenya` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuaris`
--

INSERT INTO `usuaris` (`id`, `usuari`, `contrasenya`) VALUES
(1, 'Alberto', '$2y$10$IvqsLqrmEtDweXhLr5t5ne7s1Ngu9F9DSyp6EUAJfoNCLPhE.qObm'),
(2, 'a', '$2y$10$i60lBxviuk5vH1Gg3qF/9e8UwcN9n3U0y3cDQPBxodCdGFC.be..W'),
(3, 'Proves', '$2y$10$jGAVhyTwqX0L5RF9FeliFOrhOzzQESv/TxZVBqgTNU0ValpsOXKza'),
(4, 'Xavi', '$2y$10$U5yGmoOTsxcX7LHoHN5ZS.iUMM9QMwXhg91lYj.fUGiEfazYCi376');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_usuari_id` (`usuari_id`);

--
-- Indices de la tabla `usuaris`
--
ALTER TABLE `usuaris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuari` (`usuari`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articles`
--
ALTER TABLE `articles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `usuaris`
--
ALTER TABLE `usuaris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_usuari_id` FOREIGN KEY (`usuari_id`) REFERENCES `usuaris` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
