-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-08-2024 a las 04:41:43
-- Versión del servidor: 10.11.8-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u160637135_AmoxtliJap`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_login`
--

CREATE TABLE `intentos_login` (
  `correo` varchar(255) NOT NULL,
  `ip_usuario` varchar(255) NOT NULL,
  `intentos_fallidos` int(11) DEFAULT NULL,
  `ultimo_intento` datetime DEFAULT NULL,
  `bloqueado_hasta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `intentos_login`
--

INSERT INTO `intentos_login` (`correo`, `ip_usuario`, `intentos_fallidos`, `ultimo_intento`, `bloqueado_hasta`) VALUES
('gabrielcarrizales2207@gmail.com', '189.155.196.227', 0, '2024-08-21 20:37:20', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `register`
--

CREATE TABLE `register` (
  `ID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `CodeV` varchar(255) NOT NULL,
  `verification` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `register`
--

INSERT INTO `register` (`ID`, `Username`, `email`, `Password`, `CodeV`, `verification`) VALUES
(43, 'Evelyn_Carrizales', 'evelyncarrizales1018@gmail.com', '$2y$10$Q0B/FszziAAjWsmJKjyK0.5CY8x.yn.tOoA/1ibgA.iZu2LqI3dKW', '', 0),
(44, 'Gabriel_Carrizales', 'gabrielcarrizales2207@gmail.com', '$2y$10$s.I.IFCbN5yltXFq4aD1NeQ47J/8NAXYaVhBMs.v6LPgPRT7RbeiG', '', 0),
(48, 'bryam', 'bryam.glvn0206@gmail.com', '$2y$10$uJ7kQIWUYftDanRjUmHOx.DVOs/XnaEokwxx7/rbaa0riBBXY8bta', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sensores`
--

CREATE TABLE `sensores` (
  `id_lectura` int(11) NOT NULL,
  `id_dispositivo` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `posicion` varchar(50) NOT NULL,
  `caida_detectada` tinyint(1) NOT NULL,
  `gps_latitud` decimal(9,6) NOT NULL,
  `gps_longitud` decimal(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `sensores`
--

INSERT INTO `sensores` (`id_lectura`, `id_dispositivo`, `timestamp`, `posicion`, `caida_detectada`, `gps_latitud`, `gps_longitud`) VALUES
(241, 31, '2024-08-16 02:24:24', 'Acostado Boca Arriba', 1, 24.437473, -104.116012),
(242, 31, '2024-08-16 03:05:28', 'Acostado Boca Arriba', 1, 24.437301, -104.115991),
(243, 43, '2024-08-16 03:22:21', 'Acostado Boca Arriba', 1, 24.437397, -104.116093),
(244, 44, '2024-08-16 03:27:07', 'Acostado Boca Arriba', 1, 24.437453, -104.116177),
(245, 44, '2024-08-16 05:11:36', 'Acostado sobre su Lado Izquierdo', 1, 24.436749, -104.116529),
(246, 44, '2024-08-16 06:03:14', 'Acostado Boca Arriba', 1, 24.437495, -104.116211),
(247, 44, '2024-08-16 06:04:00', 'Acostado Boca Abajo', 1, 24.437367, -104.116359),
(249, 44, '2024-08-18 02:33:16', 'Acostado sobre su Lado Derecho', 1, 23.635823, -100.645101),
(251, 44, '2024-08-18 02:36:20', 'Acostado Boca Arriba', 1, 23.635798, -100.645103),
(252, 44, '2024-08-18 01:30:48', 'Acostado Boca Abajo', 1, 23.635757, -100.645213),
(253, 44, '2024-08-18 01:36:26', 'Acostado Boca Arriba', 1, 23.635792, -100.645152),
(254, 44, '2024-08-18 01:59:07', 'Posicionado sobre su Lado Izquierdo', 1, 23.635844, -100.645245),
(255, 44, '2024-08-18 14:25:12', 'Posicionado sobre su Lado Derecho', 1, 23.638188, -100.637093),
(256, 44, '2024-08-18 14:25:32', 'Posicionado Boca Arriba', 1, 23.638342, -100.636762),
(258, 44, '2024-08-18 22:04:32', 'Posicionado Boca Abajo', 1, 24.437408, -104.116126),
(259, 44, '2024-08-19 14:20:57', 'Posicionado Boca Arriba', 1, 24.020216, -104.672404),
(260, 44, '2024-08-19 14:23:13', 'Posicionado sobre su Lado Derecho', 1, 24.019454, -104.663788),
(261, 44, '2024-08-19 14:23:32', 'Posicionado Boca Arriba', 1, 24.019259, -104.662492),
(262, 44, '2024-08-19 14:41:06', 'Posicionado Boca Arriba', 1, 24.016602, -104.651783),
(263, 44, '2024-08-20 14:21:04', 'Posicionado Boca Arriba', 1, 24.437476, -104.116046);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `sensores`
--
ALTER TABLE `sensores`
  ADD PRIMARY KEY (`id_lectura`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `register`
--
ALTER TABLE `register`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `sensores`
--
ALTER TABLE `sensores`
  MODIFY `id_lectura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
