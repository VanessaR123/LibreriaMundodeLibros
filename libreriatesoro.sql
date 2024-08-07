-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-08-2024 a las 01:53:50
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
-- Base de datos: `libreriatesoro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `FolioPedido` int(11) NOT NULL,
  `IDUsuario` varchar(10) DEFAULT NULL,
  `TituloLibro` varchar(100) NOT NULL,
  `Autor` varchar(100) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `FechaPedido` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IDUsuario` varchar(10) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `ApellidoPaterno` varchar(50) NOT NULL,
  `ApellidoMaterno` varchar(50) DEFAULT NULL,
  `Edad` int(11) NOT NULL,
  `Sexo` enum('M','F') NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefono` varchar(15) DEFAULT NULL,
  `TipoUsuario` enum('PL','CL') NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IDUsuario`, `Nombre`, `ApellidoPaterno`, `ApellidoMaterno`, `Edad`, `Sexo`, `Email`, `Telefono`, `TipoUsuario`, `Password`) VALUES
('0000', 'Juan', 'Pérez', 'González', 35, 'M', 'juanperez@example.com', '555-1234', 'PL', 'Progweb2#'),
('1111', 'Vanessa', 'Caballero', 'Casados', 27, 'F', 'casados@gmail.com', '2281255290', '', '$2y$10$.eLxCn2Roa.5hW94v.CSuOdKlso3O/hld19.xDvqJHVjuOjCk0xvG'),
('2222', 'Yohan', 'Perez', 'Casados', 25, 'M', 'yohan123@hotmail.com', '895456', 'CL', '$2y$10$mKEXruVsle2P9hlrb1F6Hud/Hh3oAk5TRR3Acq0YvJiItQ5B1/jd6'),
('3333', 'Yazmin', 'García', 'Luna', 35, 'F', 'lunay@hotmail.com', '5789615', 'CL', '$2y$10$bPowl6N2azRw6kQhHesVr.EWK4NwibcihtZoNgDtbaGuouIcrn2dy'),
('9999', 'Ana', 'López', 'Martínez', 28, 'F', 'analopez@example.com', '555-5678', 'CL', 'Progweb2#');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`FolioPedido`),
  ADD KEY `IDUsuario` (`IDUsuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IDUsuario`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `FolioPedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`IDUsuario`) REFERENCES `usuarios` (`IDUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
