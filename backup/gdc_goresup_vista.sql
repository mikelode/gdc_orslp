-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-12-2017 a las 17:26:02
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gdc_goresup`
--

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vtramdestinatario`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vtramdestinatario` (
`clave` int(11) unsigned
,`denominacion` text
,`tabla` varchar(15)
,`idtabla` varchar(1)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vtramdestinatario`
--
DROP TABLE IF EXISTS `vtramdestinatario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtramdestinatario`  AS  select `p`.`tprId` AS `clave`,`p`.`tprFulName` AS `denominacion`,'tramPersona' AS `tabla`,'1' AS `idtabla` from `trampersona` `p` union all select `d`.`depId` AS `clave`,`d`.`depDsc` AS `denominacion`,'tramDependencia' AS `tabla`,'2' AS `idtabla` from `tramdependencia` `d` ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
