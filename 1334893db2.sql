-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8080
-- Tiempo de generación: 19-07-2018 a las 09:08:38
-- Versión del servidor: 10.2.16-MariaDB
-- Versión de PHP: 7.1.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `1334893db2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleta_cabecera`
--

CREATE TABLE `boleta_cabecera` (
  `id_boleta` int(7) NOT NULL,
  `cod_tip_operacion` int(2) NOT NULL,
  `num_boleta` varchar(20) NOT NULL,
  `fech_emision` date NOT NULL,
  `cod_local_emisor` varchar(3) DEFAULT NULL,
  `cod_tipo_doc_usuario` varchar(1) DEFAULT NULL,
  `num_doc_usuario` varchar(15) DEFAULT NULL,
  `tip_moneda` varchar(3) DEFAULT NULL,
  `mto_oper_gravadas` double(12,2) DEFAULT NULL,
  `mto_igv` double(12,2) DEFAULT NULL,
  `imp_total_venta` double(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `boleta_cabecera`
--

INSERT INTO `boleta_cabecera` (`id_boleta`, `cod_tip_operacion`, `num_boleta`, `fech_emision`, `cod_local_emisor`, `cod_tipo_doc_usuario`, `num_doc_usuario`, `tip_moneda`, `mto_oper_gravadas`, `mto_igv`, `imp_total_venta`) VALUES
(1, 1, '-', '2018-07-19', '01', '0', '12345678', 'PEN', 3.40, 0.00, 3.40),
(2, 1, '-', '2018-07-05', '01', '0', '12345678', 'PEN', 40.60, 0.00, 40.60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleta_detalle`
--

CREATE TABLE `boleta_detalle` (
  `id_detalle` int(7) NOT NULL,
  `id_boleta` int(7) NOT NULL,
  `cod_producto` varchar(30) NOT NULL,
  `ctd_unidad_item` int(12) DEFAULT NULL,
  `cod_tip_sis_isc` varchar(2) DEFAULT NULL,
  `mto_precio_venta_item` double(12,2) DEFAULT NULL,
  `mto_valor_venta_item` double(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `boleta_detalle`
--

INSERT INTO `boleta_detalle` (`id_detalle`, `id_boleta`, `cod_producto`, `ctd_unidad_item`, `cod_tip_sis_isc`, `mto_precio_venta_item`, `mto_valor_venta_item`) VALUES
(1, 1, '123', 1, '0', 3.40, 3.40),
(2, 2, '3', 1, '0', 40.60, 40.60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cod_tipo_doc_usuario` varchar(1) NOT NULL,
  `num_doc_usuario` varchar(15) NOT NULL,
  `rzn_social_usuario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cod_tipo_doc_usuario`, `num_doc_usuario`, `rzn_social_usuario`) VALUES
('1', '12345678', 'Miguel Angel'),
('1', '4324234', 'Juan Martinez'),
('1', '594864964', 'Pioneros E.I.R.L'),
('1', '72244550', 'Alvaro Apaza'),
('1', '72244552', 'Javier Humpire'),
('7', '123321', 'Jose Perez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local_anexo_emisor`
--

CREATE TABLE `local_anexo_emisor` (
  `cod_local_emisor` varchar(3) NOT NULL,
  `des_local_emisor` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `local_anexo_emisor`
--

INSERT INTO `local_anexo_emisor` (`cod_local_emisor`, `des_local_emisor`) VALUES
('01', 'Principal'),
('02', 'Sucursal 1'),
('03', 'Sucursal 2'),
('04', 'Sucursal 3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `cod_producto` varchar(30) NOT NULL,
  `cod_producto_sunat` varchar(20) DEFAULT NULL,
  `cod_unidad_medida` varchar(3) DEFAULT NULL,
  `des_item` varchar(250) DEFAULT NULL,
  `mto_valor_unitario` double(12,2) DEFAULT NULL,
  `stock` int(5) NOT NULL,
  `mto_igv_item` double(12,2) DEFAULT NULL,
  `cod_tip_afe_igv` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`cod_producto`, `cod_producto_sunat`, `cod_unidad_medida`, `des_item`, `mto_valor_unitario`, `stock`, `mto_igv_item`, `cod_tip_afe_igv`) VALUES
('123', '0', 'mtr', 'PH', 3.40, 394, 0.00, '10'),
('1231', '0', 'lt', 'Gaseosa', 5.50, 0, 0.00, '30'),
('3', '0', 'mt', 'Dulce', 40.60, 56, 0.00, '0'),
('4232123', '0', 'tg', 'Leche', 4.90, 0, 0.00, '32'),
('56', '0', 'lt', 'Gaseosa', 5.50, 328, 0.00, '11'),
('99', '0', 'tr', 'Chocolate', 3.50, 396, 0.00, '32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_afectacion_igv`
--

CREATE TABLE `tipo_afectacion_igv` (
  `cod_tip_afe_igv` varchar(2) NOT NULL,
  `des_tip_afe_igv` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_afectacion_igv`
--

INSERT INTO `tipo_afectacion_igv` (`cod_tip_afe_igv`, `des_tip_afe_igv`) VALUES
('10', 'Gravado -  Operacion Onerosa'),
('11', 'Gravado - Retiro por premio '),
('12', 'Gravado - Retiro por donacion '),
('13', 'Gravado - Retiro'),
('14', 'Gravado - Retiro por publicidad'),
('15', 'Gravado - Bonificaciones'),
('16', 'Gravado - Retiro por entrega a trabajadores '),
('17', 'Gravado - IVAP '),
('20', 'Exonerado - Operacion Onerosa'),
('21', 'Exonerado - Transferencia Gratuita'),
('30', 'Inafecto - Operacion Onerosa'),
('31', 'Inafecto - Retiro por Bonificacion '),
('32', 'Inafecto - Retiro '),
('33', 'Inafecto - Retiro por Muestras Medicas'),
('34', 'Inafecto -  Retiro por Convenio Colectivo'),
('35', 'Inafecto - Retiro por premio'),
('36', 'Inafecto -  Retiro por publicidad '),
('40', 'Exportacion ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_doc_identidad`
--

CREATE TABLE `tipo_doc_identidad` (
  `cod_tipo_doc_usuario` varchar(1) NOT NULL,
  `des_tipo_doc_usuario` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_doc_identidad`
--

INSERT INTO `tipo_doc_identidad` (`cod_tipo_doc_usuario`, `des_tipo_doc_usuario`) VALUES
('0', 'DOC.TRIB.NO.DOM.SIN.RUC'),
('1', 'DOC. NACIONAL DE IDENTIDAD'),
('4', 'CARNET DE EXTRANJERIA'),
('6', 'REG. UNICO DE CONTRIBUYENTES'),
('7', 'PASAPORTE'),
('A', 'CED. DIPLOMATICA DE IDENTIDAD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_operacion`
--

CREATE TABLE `tipo_operacion` (
  `cod_tip_operacion` varchar(2) NOT NULL,
  `des_tip_operacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_operacion`
--

INSERT INTO `tipo_operacion` (`cod_tip_operacion`, `des_tip_operacion`) VALUES
('01', 'Venta lnterna'),
('02', 'Exportacion'),
('03', 'No Domiciliados'),
('04', 'Venta Interna - Anticipos'),
('05', 'Venta Itinerante '),
('06', 'Factura Guia'),
('07', 'Venta Arroz Pilado '),
('08', 'Factura - Comprobante de Percepcion'),
('10', 'Factura - Guia remitente'),
('11', 'Factura - Guia transportista'),
('12', 'Boleta de venta - Comprobante de Percepcion.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_sistema_isc`
--

CREATE TABLE `tipo_sistema_isc` (
  `cod_tip_sis_isc` varchar(2) NOT NULL,
  `des_tip_sis_isc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_sistema_isc`
--

INSERT INTO `tipo_sistema_isc` (`cod_tip_sis_isc`, `des_tip_sis_isc`) VALUES
('01', 'Sistema al valor (Apéndice IV, lit. A – T.U.O IGV e ISC) '),
('02', 'Aplicación del Monto Fijo (Apéndice IV, lit. B – T.U.O IGV e ISC)'),
('03', 'Sistema de Precios de Venta al Público (Apéndice IV, lit. C – T.U.O IGV e ISC) ');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `boleta_cabecera`
--
ALTER TABLE `boleta_cabecera`
  ADD PRIMARY KEY (`id_boleta`);

--
-- Indices de la tabla `boleta_detalle`
--
ALTER TABLE `boleta_detalle`
  ADD PRIMARY KEY (`id_detalle`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cod_tipo_doc_usuario`,`num_doc_usuario`);

--
-- Indices de la tabla `local_anexo_emisor`
--
ALTER TABLE `local_anexo_emisor`
  ADD PRIMARY KEY (`cod_local_emisor`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`cod_producto`);

--
-- Indices de la tabla `tipo_afectacion_igv`
--
ALTER TABLE `tipo_afectacion_igv`
  ADD PRIMARY KEY (`cod_tip_afe_igv`);

--
-- Indices de la tabla `tipo_doc_identidad`
--
ALTER TABLE `tipo_doc_identidad`
  ADD PRIMARY KEY (`cod_tipo_doc_usuario`);

--
-- Indices de la tabla `tipo_operacion`
--
ALTER TABLE `tipo_operacion`
  ADD PRIMARY KEY (`cod_tip_operacion`);

--
-- Indices de la tabla `tipo_sistema_isc`
--
ALTER TABLE `tipo_sistema_isc`
  ADD PRIMARY KEY (`cod_tip_sis_isc`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `boleta_cabecera`
--
ALTER TABLE `boleta_cabecera`
  MODIFY `id_boleta` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `boleta_detalle`
--
ALTER TABLE `boleta_detalle`
  MODIFY `id_detalle` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
