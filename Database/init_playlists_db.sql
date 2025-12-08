-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-12-2025 a las 19:55:00
-- Versión del servidor: 10.1.32-MariaDB
-- Versión de PHP: 7.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
 /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
 /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 /*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `playlists_db`
--

--
-- Crear base de datos si no existe
--

CREATE DATABASE IF NOT EXISTS `playlists_db` CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `playlists_db`;

-- --------------------------------------------------------
-- Tabla de usuarios
-- --------------------------------------------------------
CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario` VARCHAR(32) COLLATE utf8_spanish_ci NOT NULL UNIQUE,
    `password` VARCHAR(255) COLLATE utf8_spanish_ci NOT NULL,
    `nombre` VARCHAR(64) COLLATE utf8_spanish_ci NOT NULL,
    `email` VARCHAR(64) COLLATE utf8_spanish_ci NOT NULL UNIQUE,
    `rol` ENUM('admin','user') NOT NULL DEFAULT 'user',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------
-- Tabla de canciones
-- --------------------------------------------------------
CREATE TABLE `songs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(128) COLLATE utf8_spanish_ci NOT NULL,
    `artista` VARCHAR(128) COLLATE utf8_spanish_ci NOT NULL,
    `album` VARCHAR(128) COLLATE utf8_spanish_ci DEFAULT NULL,
    `duracion` VARCHAR(8) COLLATE utf8_spanish_ci NOT NULL, -- formato mm:ss
    `portada` VARCHAR(255) COLLATE utf8_spanish_ci DEFAULT NULL,
    `genero` VARCHAR(64) COLLATE utf8_spanish_ci NOT NULL,
    `proposito` VARCHAR(64) COLLATE utf8_spanish_ci DEFAULT NULL,
    `year` INT(4) DEFAULT NULL,
    `licencia` VARCHAR(64) COLLATE utf8_spanish_ci NOT NULL,
    `reproducciones` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------
-- Tabla de playlists
-- --------------------------------------------------------
CREATE TABLE `playlists` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `nombre` VARCHAR(128) COLLATE utf8_spanish_ci NOT NULL,
    `descripcion` TEXT COLLATE utf8_spanish_ci,
    `imagen` VARCHAR(255) COLLATE utf8_spanish_ci DEFAULT NULL,
    `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `ultima_modificacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `visibilidad` ENUM('publica','privada') NOT NULL DEFAULT 'privada',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------
-- Relación canciones-playlists (muchos a muchos)
-- --------------------------------------------------------
CREATE TABLE `playlist_songs` (
    `playlist_id` INT(11) NOT NULL,
    `song_id` INT(11) NOT NULL,
    `orden` INT(11) DEFAULT NULL,
    PRIMARY KEY (`playlist_id`,`song_id`),
    FOREIGN KEY (`playlist_id`) REFERENCES `playlists`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`song_id`) REFERENCES `songs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------
-- Tabla de favoritos
-- --------------------------------------------------------
CREATE TABLE `favorites` (
    `user_id` INT(11) NOT NULL,
    `song_id` INT(11) NOT NULL,
    PRIMARY KEY (`user_id`,`song_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`song_id`) REFERENCES `songs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------
-- Historial de reproducción
-- --------------------------------------------------------
CREATE TABLE `history` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `song_id` INT(11) NOT NULL,
    `fecha_reproduccion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`song_id`) REFERENCES `songs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------
-- Datos iniciales de prueba (solo admin)
-- --------------------------------------------------------
INSERT INTO `users` (`usuario`, `password`, `nombre`, `email`, `rol`) VALUES
('admin', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdefghi', 'Administrador', 'admin@demo.com', 'admin');

COMMIT;

-- Adminsitrador:
-- Usuario -> admin
-- Contraseñas -> Admin1234

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
