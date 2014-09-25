-- phpMyAdmin SQL Dump
-- version 4.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Sep 15, 2014 at 10:48 AM
-- Server version: 5.1.73
-- PHP Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `winnersfund`
--

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE IF NOT EXISTS `criteria` (
`id` bigint(20) unsigned NOT NULL,
  `section` varchar(50) NOT NULL DEFAULT 'node',
  `title` tinytext,
  `description` text,
  `order` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Criterios de puntuación' AUTO_INCREMENT=28 ;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`id`, `section`, `title`, `description`, `order`) VALUES
(5, 'project', 'Es original', 'donde va esta descripción? donde esta el tool tip?\r\n\r\nHola, este tooltip ira en el formulario de revision', 1),
(6, 'project', 'Es eficaz en su estrategia de comunicación', '', 2),
(7, 'project', 'Aporta información suficiente del proyecto', '', 3),
(8, 'project', 'Aporta productos, servicios o valores “deseables” para la comunidad', '', 4),
(9, 'project', 'Es afín a la cultura abierta', '', 5),
(10, 'project', 'Puede crecer, es escalable', '', 6),
(11, 'project', 'Son coherentes los recursos solicitados con los objetivos y el tiempo de desarrollo', '', 7),
(12, 'project', 'Riesgo proporcional al grado de beneficios (sociales, culturales y/o económicos)', 'Test descripción de un criterio...', 8),
(13, 'owner', 'Posee buena reputación en su sector', '', 1),
(14, 'owner', 'Ha trabajado con organizaciones y colectivos con buena reputación', '', 2),
(15, 'owner', 'Aporta información sobre experiencias anteriores (éxitos y fracasos)', '', 3),
(16, 'owner', 'Tiene capacidades para llevar a cabo el proyecto', '', 4),
(17, 'owner', 'Cuenta con un equipo formado', '', 5),
(18, 'owner', 'Cuenta con una comunidad de seguidores', '', 6),
(19, 'owner', 'Tiene visibilidad en la red', '', 7),
(20, 'reward', 'Es viable (su coste está incluido en la producción del proyecto)', '', 1),
(21, 'reward', 'Puede tener efectos positivos, transformadores (sociales, culturales, empresariales)', '', 2),
(22, 'reward', 'Aporta conocimiento nuevo, de difícil acceso o en proceso de desaparecer', '', 3),
(23, 'reward', 'Aporta oportunidades de generar economía alrededor', '', 4),
(24, 'reward', 'Da libertad en el uso de sus resultados (es reproductible)', '', 5),
(25, 'reward', 'Ofrece un retorno atractivo (por original, por útil, por inspirador... )', '', 6),
(26, 'reward', 'Cuenta con actualizaciones', '', 7),
(27, 'reward', 'Integra a la comunidad (a los seguidores, cofinanciadores, a un grupo social)', '', 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
