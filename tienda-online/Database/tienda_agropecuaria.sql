-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2025 a las 02:05:58
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
-- Base de datos: `tienda_agropecuaria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime NOT NULL,
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id`, `nombre`, `email`, `telefono`, `mensaje`, `fecha`, `leido`) VALUES
(1, 'Santiago', 'santiagods1112@gmail.com', '02123213854', 'hi', '2025-05-15 15:59:30', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `stock`, `imagen`, `fecha_creacion`) VALUES
(1, 'Manguera de riego', 'Manguera de riego 3/4 de 100 metros', 50.00, 'riego', 10, 'Manguera.jpg', '2025-05-16 15:55:51'),
(2, 'Alimento para pollos', 'Saco de alimento para pollos y engorde 40kg', 50.00, 'Alimentos', 30, 'Alimento_pollos.jpg', '2025-05-16 16:07:55'),
(3, 'Machete para rozar', 'Machete rozador de 22 pulgadas nuevo', 25.00, 'Herramientas', 15, 'Machete.jpg', '2025-05-16 16:07:55'),
(4, 'Semillas de Tomate', 'Semillas de tomate marca seminis, 5000 semillas.', 35.00, 'Semillas', 15, 'Semillas_tomate.jpg', '2025-05-16 16:26:03'),
(5, 'Saco de Urea 50kg', 'Saco de urea 46% de nitrogeno, 50kg de contenido', 40.00, 'fertilizantes', 50, 'Urea.jpg', '2025-05-16 16:27:47'),
(6, 'Insecticida karate', 'Karate Zeon + 1,5 cs Lambda cihalotrin 1,5% insecticida 4 litros', 35.00, 'Insecticidas', 15, 'Insecticida_karate.jpg', '2025-05-16 16:33:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `fecha_creacion`) VALUES
(1, 'Santiago  De Sousa', 'Santiagods1112@gmail.com', '$2y$10$9FFn5V2QiNpmT80S0muEZ.bmbum7bPduNHSNOUWK6.eeWeWOkPzGK', 'usuario', '2025-05-14 17:52:31'),
(2, 'Administrador Principal', 'admin@tudominio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-05-15 19:29:04'),
(3, 'Santiago  De Sousa', 'santiagods1113@gmail.com', '$2y$10$vbFrgx8IucjBvbR31GXigeN24uqiM6ACKSxcyVtdrQ6x.WOY9BWrS', 'usuario', '2025-05-16 18:51:10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
