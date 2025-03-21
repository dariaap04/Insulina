-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-03-2025 a las 18:40:45
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
-- Base de datos: `insulinadb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comida`
--

CREATE TABLE `comida` (
  `tipo_comida` varchar(30) NOT NULL,
  `gl_1h` int(11) NOT NULL,
  `gl_2h` int(11) NOT NULL,
  `raciones` int(11) NOT NULL,
  `insulina` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_usu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comida`
--

INSERT INTO `comida` (`tipo_comida`, `gl_1h`, `gl_2h`, `raciones`, `insulina`, `fecha`, `id_usu`) VALUES
('almuerzo', 3, 2, 2, 1, '2025-03-14', 7),
('merienda', 2, 1, 1, 2, '2025-03-13', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_glucosa`
--

CREATE TABLE `control_glucosa` (
  `fecha` date NOT NULL,
  `deporte` int(11) NOT NULL,
  `lenta` int(11) NOT NULL,
  `id_usu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `control_glucosa`
--

INSERT INTO `control_glucosa` (`fecha`, `deporte`, `lenta`, `id_usu`) VALUES
('2025-03-01', 0, 30, 5),
('2025-03-02', 0, 45, 5),
('2025-03-12', 0, 48, 5),
('2025-03-13', 0, 52, 5),
('2025-03-14', 0, 15, 5),
('2025-03-14', 0, 22, 7),
('2025-03-15', 0, 29, 5),
('2025-03-22', 0, 0, 5),
('2025-03-28', 0, 0, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hiperglucemia`
--

CREATE TABLE `hiperglucemia` (
  `glucosa` int(11) NOT NULL,
  `hora` time NOT NULL,
  `correccion` int(11) NOT NULL,
  `tipo_comida` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `id_usu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hiperglucemia`
--

INSERT INTO `hiperglucemia` (`glucosa`, `hora`, `correccion`, `tipo_comida`, `fecha`, `id_usu`) VALUES
(3, '13:33:00', 2, 'almuerzo', '2025-03-14', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hipoglucemia`
--

CREATE TABLE `hipoglucemia` (
  `glucosa` int(11) NOT NULL,
  `hora` time NOT NULL,
  `tipo_comida` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `id_usu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usu` int(11) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `apellidos` varchar(25) NOT NULL,
  `usuario` varchar(25) NOT NULL,
  `contra` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usu`, `fecha_nacimiento`, `nombre`, `apellidos`, `usuario`, `contra`) VALUES
(2, '2004-02-10', 'Juan Diego', 'Profesor33', 'pepep21', '$2y$10$vQkg/12Sx.KRSwIXTe0O2eHxJDjaHUhUntEc6WX2xHzZ376L.Pq8C'),
(4, '2005-03-02', 'Yolanda', 'Iglesias', 'yolandai2', '$2y$10$aaJqJSpveqpE0VEt/z6BaOWHF1IFTyPmCVQuVnk1oTkvaidbjjTpO'),
(5, '2004-07-10', 'Dariass11', 'Alonso Presumido', 'dariaappp', '$argon2id$v=19$m=65536,t=4,p=1$b1JPLldISkUzTWwvS256Rg$eQgwy2CxB1Ew9+KrqW69iKxEdaOW9faFX5IXTwihPjc'),
(6, '2004-06-15', 'María', 'Fernández López', 'mariafl', '$argon2id$v=19$m=65536,t=4,p=1$MmxZMjFmNHFKaVBOYThjUg$2lmS+Sd/Cc2p9B4h29cnDNvgT7rIuAtI6HGr3aj311M'),
(7, '2004-06-15', 'Afrikaans', 'López', 'afrikalo', '$argon2id$v=19$m=65536,t=4,p=1$SkZhTDAzN011M1pCODlVeA$52HTatm6NSoKwqUKQnx0hECapYJBChlWR6Nv6AiB8JM'),
(8, '2003-09-19', 'Albasss1', 'Soldado Fernández', 'albasf', '$2y$10$8QRaPpGzsPLqjuOiS1icU.6KuMt5lmUEOmlHoZbUzL60Tt.N0sgA6');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comida`
--
ALTER TABLE `comida`
  ADD PRIMARY KEY (`tipo_comida`,`fecha`,`id_usu`),
  ADD KEY `fecha` (`fecha`,`id_usu`);

--
-- Indices de la tabla `control_glucosa`
--
ALTER TABLE `control_glucosa`
  ADD PRIMARY KEY (`fecha`,`id_usu`),
  ADD KEY `id_usu` (`id_usu`);

--
-- Indices de la tabla `hiperglucemia`
--
ALTER TABLE `hiperglucemia`
  ADD PRIMARY KEY (`tipo_comida`,`fecha`,`id_usu`);

--
-- Indices de la tabla `hipoglucemia`
--
ALTER TABLE `hipoglucemia`
  ADD PRIMARY KEY (`tipo_comida`,`fecha`,`id_usu`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usu`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comida`
--
ALTER TABLE `comida`
  ADD CONSTRAINT `comida_ibfk_1` FOREIGN KEY (`fecha`,`id_usu`) REFERENCES `control_glucosa` (`fecha`, `id_usu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `control_glucosa`
--
ALTER TABLE `control_glucosa`
  ADD CONSTRAINT `control_glucosa_ibfk_1` FOREIGN KEY (`id_usu`) REFERENCES `usuario` (`id_usu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `hiperglucemia`
--
ALTER TABLE `hiperglucemia`
  ADD CONSTRAINT `hiperglucemia_ibfk_1` FOREIGN KEY (`tipo_comida`,`fecha`,`id_usu`) REFERENCES `comida` (`tipo_comida`, `fecha`, `id_usu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `hipoglucemia`
--
ALTER TABLE `hipoglucemia`
  ADD CONSTRAINT `hipoglucemia_ibfk_1` FOREIGN KEY (`tipo_comida`,`fecha`,`id_usu`) REFERENCES `comida` (`tipo_comida`, `fecha`, `id_usu`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
