# ************************************************************
# Sequel Pro SQL dump
# Versão 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.35)
# Base de Dados: b7crud
# Tempo de Geração: 2019-06-21 16:40:14 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump da tabela data_values
# ------------------------------------------------------------

DROP TABLE IF EXISTS `data_values`;

CREATE TABLE `data_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `row_values` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `data_values` WRITE;
/*!40000 ALTER TABLE `data_values` DISABLE KEYS */;

INSERT INTO `data_values` (`id`, `id_user`, `row_values`)
VALUES
	(1,1,'{\"name\":\"Spider\",\"factory\":\"Ferrari\",\"color\":\"Red\"}'),
	(2,1,'{\"name\":\"Chiron\",\"factory\":\"Bugatti\",\"color\":\"Blue\"}'),
	(4,1,'{\"name\":\"Fusca\",\"factory\":\"Volkswagen\",\"color\":\"White\"}');

/*!40000 ALTER TABLE `data_values` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL DEFAULT '',
  `pass` varchar(255) NOT NULL DEFAULT '',
  `structure_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `email`, `pass`, `structure_name`)
VALUES
	(1,'suporte@b7web.com.br','$2y$10$ha5DEa.tfYTBdtbHHwCYwORk7TDLeBstLRMZ/zwqK12Au5jzrQ9B6','carros');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump da tabela users_structure
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_structure`;

CREATE TABLE `users_structure` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `column_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users_structure` WRITE;
/*!40000 ALTER TABLE `users_structure` DISABLE KEYS */;

INSERT INTO `users_structure` (`id`, `id_user`, `column_name`)
VALUES
	(2,1,'name'),
	(4,1,'factory'),
	(17,1,'color');

/*!40000 ALTER TABLE `users_structure` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
