-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-11-2020 a las 00:30:59
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_sistema_soporte`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`estado`) VALUES
(0),
(1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reportes`
--

CREATE TABLE `estados_reportes` (
  `estado_reporte` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estados_reportes`
--

INSERT INTO `estados_reportes` (`estado_reporte`) VALUES
('FALLA'),
('REPARACION'),
('SOLUCIONADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `permiso` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`permiso`) VALUES
('Administrador'),
('Tecnico'),
('Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id_reporte` int(11) NOT NULL,
  `fecha_reporte` varchar(10) NOT NULL,
  `fecha_solucion` varchar(10) DEFAULT NULL,
  `usuario` varchar(20) NOT NULL,
  `tecnico` varchar(20) DEFAULT NULL,
  `desc_reporte` text NOT NULL,
  `desc_solucion` text DEFAULT NULL,
  `estado_reporte` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario` varchar(20) NOT NULL,
  `contrasena` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `permiso` varchar(20) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`estado`);

--
-- Indices de la tabla `estados_reportes`
--
ALTER TABLE `estados_reportes`
  ADD PRIMARY KEY (`estado_reporte`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`permiso`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `fk_reportes_usuarios` (`usuario`),
  ADD KEY `fk_reportes_tecnicos` (`tecnico`),
  ADD KEY `fk_reportes_estados_reportes` (`estado_reporte`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario`),
  ADD KEY `fk_usuarios_permisos` (`permiso`),
  ADD KEY `fk_usuarios_estados` (`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `fk_reportes_estados_reportes` FOREIGN KEY (`estado_reporte`) REFERENCES `estados_reportes` (`estado_reporte`),
  ADD CONSTRAINT `fk_reportes_tecnicos` FOREIGN KEY (`tecnico`) REFERENCES `usuarios` (`usuario`),
  ADD CONSTRAINT `fk_reportes_usuarios` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_estados` FOREIGN KEY (`estado`) REFERENCES `estados` (`estado`),
  ADD CONSTRAINT `fk_usuarios_permisos` FOREIGN KEY (`permiso`) REFERENCES `permisos` (`permiso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
