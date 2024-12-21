-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 17-11-2024 a las 15:11:48
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `almacen`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha_apertura` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_cierre` datetime DEFAULT NULL,
  `monto_inicial` int NOT NULL,
  `monto_final` int DEFAULT '0',
  `estado` enum('abierta','cerrada') COLLATE utf8mb4_spanish_ci DEFAULT 'abierta',
  `total_ventas` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `usuario_id`, `fecha_apertura`, `fecha_cierre`, `monto_inicial`, `monto_final`, `estado`, `total_ventas`) VALUES
(1, 1, '2024-11-09 23:37:55', '2024-11-09 23:39:10', 12345, 78500, 'cerrada', 0),
(2, 1, '2024-11-09 23:45:06', '2024-11-09 23:48:27', 5395, 90500, 'cerrada', 0),
(3, 1, '2024-11-09 23:57:40', '2024-11-10 01:10:56', 123654, 82500, 'cerrada', 0),
(4, 1, '2024-11-10 01:11:13', '2024-11-10 01:16:13', 59562, 0, 'cerrada', 0),
(5, 1, '2024-11-10 01:16:17', '2024-11-10 01:20:37', 32432, 50000, 'cerrada', 0),
(6, 1, '2024-11-10 01:21:06', '2024-11-10 01:22:07', 1, 0, 'cerrada', 0),
(7, 1, '2024-11-10 01:23:51', '2024-11-10 01:23:54', 78, 0, 'cerrada', 0),
(8, 1, '2024-11-10 01:23:58', '2024-11-10 01:24:55', 88, 0, 'cerrada', 0),
(9, 1, '2024-11-10 01:25:03', '2024-11-10 01:25:09', 3, 0, 'cerrada', 0),
(10, 1, '2024-11-10 01:25:09', '2024-11-10 01:25:25', 3, 0, 'cerrada', 0),
(11, 1, '2024-11-10 01:25:26', '2024-11-10 01:25:29', 3, 0, 'cerrada', 0),
(12, 1, '2024-11-10 01:25:29', '2024-11-10 01:26:59', 3, 0, 'cerrada', 0),
(13, 1, '2024-11-10 01:26:59', '2024-11-10 01:27:06', 3, 0, 'cerrada', 0),
(14, 1, '2024-11-10 01:28:38', '2024-11-10 01:28:46', 1511, 0, 'cerrada', 0),
(15, 1, '2024-11-10 01:28:46', '2024-11-10 01:29:36', 1511, 0, 'cerrada', 0),
(16, 1, '2024-11-10 01:29:36', '2024-11-10 15:06:29', 1511, 0, 'cerrada', 0),
(17, 1, '2024-11-10 17:03:09', '2024-11-10 17:09:49', 15320, 0, 'cerrada', 0),
(18, 1, '2024-11-10 17:09:54', NULL, 121212, 0, 'abierta', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int NOT NULL,
  `nombre_categoria` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `aplica_peso` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`, `aplica_peso`) VALUES
(1, 'Pan', 1),
(2, 'Golosina', 0),
(3, 'Wafer', 0),
(4, 'Chocolate', 0),
(5, 'Juguetes', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int NOT NULL,
  `venta_id` int DEFAULT NULL,
  `producto_id` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `precio_unitario` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 4, 1, 1500, 1500),
(2, 1, 5, 2, 1000, 2000),
(3, 1, 6, 3, 25000, 75000),
(4, 2, 1, 1, 7500, 7500),
(5, 3, 1, 1, 7500, 7500),
(6, 3, 2, 1, 50000, 50000),
(7, 3, 4, 1, 1500, 1500),
(8, 4, 8, 2, 12000, 24000),
(9, 5, 1, 1, 7500, 7500),
(10, 6, 1, 10, 7500, 75000),
(11, 7, 6, 1, 25000, 25000),
(12, 8, 6, 1, 25000, 25000),
(13, 9, 6, 2, 25000, 50000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id` int NOT NULL,
  `venta_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `monto_devuelto` decimal(10,2) NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `codigo_barras` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `precio` int DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `id_categoria` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo_barras`, `nombre`, `precio`, `stock`, `id_categoria`) VALUES
(1, '1234567890123', 'Laptop HP 15\"', 7500, 29, NULL),
(2, '2345678901234', 'Teclado Mecánico Logitech', 50000, 41, NULL),
(3, '3456789012345', 'Mouse Inalámbrico', 25000, 19, NULL),
(4, '7806500168102', 'Monitor Samsung 24\"', 1500, 2, NULL),
(5, '5678901234567', 'Impresora Epson', 1000, 3, NULL),
(6, '6789012345678', 'Memoria USB 32GB', 25000, 80, NULL),
(7, '7890123456789', 'Audífonos Sony', 45000, 22, NULL),
(8, '8901234567890', 'Cable HDMI 2m', 12000, 193, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`) VALUES
(1, 'Juan Perez', 'juan.perez@example.com', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'Maria Gomez', 'maria.gomez@example.com', 'e80b5017098950fc58aad83c8c14978e'),
(3, 'Carlos Sanchez', 'carlos.sanchez@example.com', 'd8578edf8458ce06fbc5bb76a58c5ca4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int NOT NULL,
  `usuario_id` int DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` int DEFAULT NULL COMMENT 'total de la venta',
  `metodo_pago` enum('efectivo','tarjeta') COLLATE utf8mb4_spanish_ci NOT NULL,
  `vuelto` int DEFAULT NULL COMMENT 'monto que entrega el atendedor',
  `monto_pagado` int DEFAULT NULL COMMENT 'monto con el que paga el comprador\r\n',
  `caja_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `usuario_id`, `fecha`, `total`, `metodo_pago`, `vuelto`, `monto_pagado`, `caja_id`) VALUES
(1, 1, '2024-11-09 23:38:37', 78500, 'efectivo', 1500, 80000, 1),
(2, 1, '2024-11-09 23:45:17', 7500, 'tarjeta', 0, 0, 2),
(3, 1, '2024-11-09 23:45:45', 59000, 'efectivo', 1000, 60000, 2),
(4, 1, '2024-11-09 23:46:00', 24000, 'efectivo', 1000, 25000, 2),
(5, 1, '2024-11-09 23:59:05', 7500, 'tarjeta', 0, 0, 3),
(6, 1, '2024-11-10 00:52:21', 75000, 'efectivo', 5000, 80000, 3),
(7, 1, '2024-11-10 01:16:24', 25000, 'tarjeta', 0, 0, 5),
(8, 1, '2024-11-10 01:20:34', 25000, 'efectivo', 25000, 50000, 5),
(9, 1, '2024-11-10 17:10:02', 50000, 'tarjeta', 0, 0, 18);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_barras` (`codigo_barras`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_caja_id` (`caja_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD CONSTRAINT `cajas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD CONSTRAINT `devoluciones_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `devoluciones_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_caja_id` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`),
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
