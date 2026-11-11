-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para ibero_tienda
CREATE DATABASE IF NOT EXISTS `ibero_tienda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ibero_tienda`;

-- Volcando estructura para tabla ibero_tienda.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla ibero_tienda.productos: ~11 rows (aproximadamente)
INSERT IGNORE INTO `productos` (`id`, `nombre`, `categoria`, `precio`, `stock`, `creado_en`) VALUES
	(1, 'Portátil Ryzen 5 16GB', 'Computadores', 2450000.00, 6, '2026-11-04 00:49:23'),
	(2, 'Mouse Vertical Ergonómico', 'Accesorios', 68000.00, 7, '2026-11-04 00:49:23'),
	(3, 'Monitor IPS 27 Pulgadas', 'Monitores', 790000.00, 4, '2026-11-04 00:49:23'),
	(4, 'Teclado Mecánico RGB', 'Accesorios', 185000.00, 7, '2026-11-04 00:49:23'),
	(5, 'Disco Sólido SSD 1TB NVMe', 'Almacenamiento', 310000.00, 9, '2026-11-04 00:49:23'),
	(6, 'Portátil Ryzen 5 16GB', 'Computadores', 2450000.00, 6, '2026-11-04 00:51:05'),
	(7, 'Mouse Vertical Ergonómico', 'Accesorios', 68000.00, 15, '2026-11-04 00:51:05'),
	(8, 'Monitor IPS 27 Pulgadas', 'Monitores', 790000.00, 4, '2026-11-04 00:51:05'),
	(9, 'Teclado Mecánico RGB', 'Accesorios', 185000.00, 7, '2026-11-04 00:51:05'),
	(10, 'Disco Sólido SSD 1TB NVMe', 'Almacenamiento', 310000.00, 9, '2026-11-04 00:51:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
