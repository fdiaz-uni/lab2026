-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-11-2023 a las 08:27:54
-- Versión del servidor: 8.0.35-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `twebV2` Modificada, añadiendo tabla `Usuario` y relacionándola con tabla `Persona`
--
CREATE DATABASE IF NOT EXISTS `twebV2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `twebV2`;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `Usuario`
--
DROP TABLE IF EXISTS `Usuario`;
CREATE TABLE `Usuario` (
  `cId` int NOT NULL AUTO_INCREMENT,
  `cNombre` varchar(60) NOT NULL,
  `cEmail` varchar(60) NOT NULL,
  `cPassword` varchar(255) NOT NULL, -- Longitud suficiente para usar luego password_hash()
  PRIMARY KEY (`cId`),
  UNIQUE KEY `cEmail` (`cEmail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Usuarios de prueba por defecto (Contraseña en texto plano, hashear cuando se implemente funcionalidad de registro de usuario)
INSERT INTO `Usuario` (`cId`, `cNombre`, `cEmail`, `cPassword`) VALUES
(1, 'usuario1', 'usuario1@uva.es', '123456'),
(2, 'user2', 'user2@uva.es', '123456');

--
-- Estructura de tabla para la tabla `Persona`. Ojo!!! Añadiendo FK cIdUsuario (usuario al que pertenecen los contactos/personas)
--
DROP TABLE IF EXISTS `Persona`;
CREATE TABLE `Persona` (
  `cId` int NOT NULL AUTO_INCREMENT,
  `cIdUsuario` int NOT NULL,
  `cNombre` varchar(60) NOT NULL,
  `cEmail` varchar(60) NOT NULL,
  `cWebsite` varchar(120) DEFAULT NULL,
  `cComentario` varchar(300) DEFAULT NULL,
  `cSexo` enum('H','M') NOT NULL,
  PRIMARY KEY (`cId`),
  KEY `fk_persona_usuario` (`cIdUsuario`),
  -- Un mismo usuario no puede repetir email de contacto, pero dos usuarios distintos sí pueden tener el mismo contacto
  UNIQUE KEY `uk_usuario_email` (`cIdUsuario`, `cEmail`),
  CONSTRAINT `fk_persona_usuario` FOREIGN KEY (`cIdUsuario`) REFERENCES `Usuario` (`cId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;