-- Aliment(idAli, nomAli, kcal, lipideAli, glucideAli, proteineAli, uniteAli)
-- Recette(idRecette, nomRecette)
-- Contient(#idRecette, #idAli, quantite)

SET NAMES utf8;
SET time_zone = '+00:00';

--
-- Table structure for table `aliment`
--
DROP TABLE IF EXISTS `aliment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aliment` (
  `idAli` int(11) NOT NULL AUTO_INCREMENT,
  `nomAli` text NOT NULL,
  `kcal` int(11) NOT NULL,
  `lipideAli` decimal(9,3) NOT NULL,
  `glucideAli` decimal(9,3) NOT NULL,
  `proteineAli` decimal(9,3) NOT NULL,
  PRIMARY KEY (`idAli`)
) ENGINE=InnoDB AUTO_INCREMENT=2807 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40000 ALTER TABLE `aliment` DISABLE KEYS */;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `ratioUser` double(4,2) NOT NULL,
  `hash` varchar(255),
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `contenuRec`;
DROP TABLE IF EXISTS `recette`;
CREATE TABLE `recette` (
  `idRec` int(11) NOT NULL AUTO_INCREMENT,
  `nomRec` varchar(255) NOT NULL,
  `ratioRec` decimal(9,2) NOT NULL,
  `kcal` decimal(9,2) NOT NULL,
  `typeRec` varchar(255),
  `email` varchar(255) NOT NULL ,
  `tokenRec` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  PRIMARY KEY (`idRec`),
  FOREIGN KEY (`email`) REFERENCES user(`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `contenuRec` (
  `idRec` int(11) NOT NULL,
  `idAli` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`idRec`, `idAli`),
  FOREIGN KEY (`idRec`) REFERENCES recette(`idRec`),
  FOREIGN KEY (`idAli`) REFERENCES aliment(`idAli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



LOCK TABLES `aliment` WRITE;
