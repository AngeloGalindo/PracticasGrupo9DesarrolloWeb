-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: localhost    Database: nexthor_hospital
-- ------------------------------------------------------
-- Server version	5.6.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `consulta`
--

DROP TABLE IF EXISTS `consulta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consulta` (
  `idconsulta` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_cita` datetime DEFAULT NULL,
  `iddoctor` int(11) NOT NULL DEFAULT '1',
  `costo` decimal(10,2) DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_final` datetime DEFAULT NULL,
  `estado` enum('Espera','Consulta','Finalizada') NOT NULL DEFAULT 'Espera',
  `idcuenta` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idconsulta`),
  KEY `fk_consulta_iddoctor_idx` (`iddoctor`),
  KEY `fk_consulta_idcuenta_idx` (`idcuenta`),
  CONSTRAINT `fk_consulta_idcuenta` FOREIGN KEY (`idcuenta`) REFERENCES `cuenta` (`idcuenta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_consulta_iddoctor` FOREIGN KEY (`iddoctor`) REFERENCES `doctor` (`iddoctor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consulta`
--

LOCK TABLES `consulta` WRITE;
/*!40000 ALTER TABLE `consulta` DISABLE KEYS */;
INSERT INTO `consulta` VALUES (1,'2015-10-16 00:00:00',1,NULL,'2015-10-16 23:23:07',NULL,'Consulta',4),(2,'2015-10-15 00:00:00',1,9789.00,NULL,'2015-10-16 23:22:58','Finalizada',4),(3,'2015-10-05 00:00:00',1,324.00,'2015-10-16 23:24:09',NULL,'Consulta',4),(4,'2015-10-13 00:00:00',1,NULL,NULL,NULL,'Espera',6);
/*!40000 ALTER TABLE `consulta` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER `nexthor_hospital`.`consulta_BEFORE_UPDATE` BEFORE UPDATE ON `consulta` FOR EACH ROW
BEGIN
	if old.estado != new.estado then
		if new.estado = 'Consulta' then
			set new.fecha_inicio = now();
		end if;
        if new.estado = 'Finalizada' then
			set new.fecha_final = now();
		end if;
    end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `continente`
--

DROP TABLE IF EXISTS `continente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `continente` (
  `idcontinente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idcontinente`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `continente`
--

LOCK TABLES `continente` WRITE;
/*!40000 ALTER TABLE `continente` DISABLE KEYS */;
INSERT INTO `continente` VALUES (1,'América','Activo'),(2,'Europa','Activo'),(3,'África','Activo'),(4,'Asia ','Activo'),(5,'Oceanía','Activo');
/*!40000 ALTER TABLE `continente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuenta`
--

DROP TABLE IF EXISTS `cuenta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuenta` (
  `idcuenta` int(11) NOT NULL AUTO_INCREMENT,
  `idpaciente` int(11) NOT NULL DEFAULT '1',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `estado` enum('Abierta','Cerrada') NOT NULL DEFAULT 'Abierta',
  PRIMARY KEY (`idcuenta`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuenta`
--

LOCK TABLES `cuenta` WRITE;
/*!40000 ALTER TABLE `cuenta` DISABLE KEYS */;
INSERT INTO `cuenta` VALUES (1,1,'2015-10-16',NULL,'Abierta'),(2,1,'2015-10-16',NULL,'Abierta'),(3,1,'2015-10-16',NULL,'Abierta'),(4,1,'2015-10-16',NULL,'Abierta'),(5,2,'2015-10-09',NULL,'Abierta'),(6,4,'2015-02-08',NULL,'Abierta'),(7,5,'2015-10-05',NULL,'Abierta');
/*!40000 ALTER TABLE `cuenta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamento`
--

DROP TABLE IF EXISTS `departamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departamento` (
  `iddepartamento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `idpais` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`iddepartamento`),
  KEY `fk_departamento_idpais_idx` (`idpais`),
  CONSTRAINT `fk_departamento_idpais` FOREIGN KEY (`idpais`) REFERENCES `pais` (`idpais`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamento`
--

LOCK TABLES `departamento` WRITE;
/*!40000 ALTER TABLE `departamento` DISABLE KEYS */;
INSERT INTO `departamento` VALUES (1,'Guatemala','Activo',1);
/*!40000 ALTER TABLE `departamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor`
--

DROP TABLE IF EXISTS `doctor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor` (
  `iddoctor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `cui` varchar(45) DEFAULT NULL,
  `idturno` int(11) NOT NULL DEFAULT '1',
  `apellido` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`iddoctor`),
  KEY `fk_doctor_idturno_idx` (`idturno`),
  CONSTRAINT `fk_doctor_idturno` FOREIGN KEY (`idturno`) REFERENCES `turno` (`idturno`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor`
--

LOCK TABLES `doctor` WRITE;
/*!40000 ALTER TABLE `doctor` DISABLE KEYS */;
INSERT INTO `doctor` VALUES (1,'Carlos','Activo','742189047213094',1,'Marroquin','ciudad','21341212'),(2,'Roberto','Activo','742189047213423',2,'Lemus','ciudad','21341212'),(3,'Sara','Activo','742189047213423',3,'Villasol','ciudad','21341212');
/*!40000 ALTER TABLE `doctor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_especialidad`
--

DROP TABLE IF EXISTS `doctor_especialidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_especialidad` (
  `iddoctor_especialidad` int(11) NOT NULL AUTO_INCREMENT,
  `iddoctor` int(11) NOT NULL DEFAULT '1',
  `idespecialidad` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`iddoctor_especialidad`),
  KEY `fk_doctor_especialidad_iddoctor_idx` (`iddoctor`),
  KEY `fk_doctor_especialidad_idespecialidad_idx` (`idespecialidad`),
  CONSTRAINT `fk_doctor_especialidad_iddoctor` FOREIGN KEY (`iddoctor`) REFERENCES `doctor` (`iddoctor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doctor_especialidad_idespecialidad` FOREIGN KEY (`idespecialidad`) REFERENCES `especialidad` (`idespecialidad`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_especialidad`
--

LOCK TABLES `doctor_especialidad` WRITE;
/*!40000 ALTER TABLE `doctor_especialidad` DISABLE KEYS */;
INSERT INTO `doctor_especialidad` VALUES (1,1,1);
/*!40000 ALTER TABLE `doctor_especialidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_servicio_medico_prestado`
--

DROP TABLE IF EXISTS `doctor_servicio_medico_prestado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctor_servicio_medico_prestado` (
  `iddoctor_servicio_medico_prestado` int(11) NOT NULL AUTO_INCREMENT,
  `iddoctor` int(11) NOT NULL DEFAULT '1',
  `idservicio_medico_prestado` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`iddoctor_servicio_medico_prestado`),
  KEY `fkdoctor_servicio_medico_prestado_iddoctor_idx` (`iddoctor`),
  KEY `fkdoctor_servicio_medico_prestado_idservicio_idx` (`idservicio_medico_prestado`),
  CONSTRAINT `fkdoctor_servicio_medico_prestado_iddoctor` FOREIGN KEY (`iddoctor`) REFERENCES `doctor` (`iddoctor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fkdoctor_servicio_medico_prestado_idservicio` FOREIGN KEY (`idservicio_medico_prestado`) REFERENCES `servicio_medico_prestado` (`idservicio_medico_prestado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_servicio_medico_prestado`
--

LOCK TABLES `doctor_servicio_medico_prestado` WRITE;
/*!40000 ALTER TABLE `doctor_servicio_medico_prestado` DISABLE KEYS */;
INSERT INTO `doctor_servicio_medico_prestado` VALUES (1,1,1),(2,1,2);
/*!40000 ALTER TABLE `doctor_servicio_medico_prestado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado` (
  `idempleado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `cui` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `idhospital` int(11) NOT NULL DEFAULT '1',
  `apellido` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idempleado`),
  KEY `fk_empleado_idturno_idx` (`idhospital`),
  CONSTRAINT `fk_empleado_idhospital` FOREIGN KEY (`idhospital`) REFERENCES `hospital` (`idhospital`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES (1,'Pedro','9098012','3214123','ciudad','Activo',1,'Aguirre');
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especialidad`
--

DROP TABLE IF EXISTS `especialidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `especialidad` (
  `idespecialidad` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idespecialidad`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especialidad`
--

LOCK TABLES `especialidad` WRITE;
/*!40000 ALTER TABLE `especialidad` DISABLE KEYS */;
INSERT INTO `especialidad` VALUES (1,'Cardiologo','Activo'),(2,'Pediatra','Activo');
/*!40000 ALTER TABLE `especialidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `habitacion`
--

DROP TABLE IF EXISTS `habitacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habitacion` (
  `idhabitacion` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(45) DEFAULT NULL,
  `idsala` int(11) NOT NULL DEFAULT '1',
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idhabitacion`),
  KEY `fk_habitacion_sala_idx` (`idsala`),
  CONSTRAINT `fk_habitacion_sala` FOREIGN KEY (`idsala`) REFERENCES `sala` (`idsala`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `habitacion`
--

LOCK TABLES `habitacion` WRITE;
/*!40000 ALTER TABLE `habitacion` DISABLE KEYS */;
INSERT INTO `habitacion` VALUES (1,'1',1,'Activo'),(3,'E1',1,'Activo'),(4,'E2',1,'Activo');
/*!40000 ALTER TABLE `habitacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hospital`
--

DROP TABLE IF EXISTS `hospital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hospital` (
  `idhospital` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT NULL,
  `idmunicipio` int(11) NOT NULL,
  PRIMARY KEY (`idhospital`),
  KEY `fk_hospital_idmunicipio_idx` (`idmunicipio`),
  CONSTRAINT `fk_hospital_idmunicipio` FOREIGN KEY (`idmunicipio`) REFERENCES `municipio` (`idmunicipio`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hospital`
--

LOCK TABLES `hospital` WRITE;
/*!40000 ALTER TABLE `hospital` DISABLE KEYS */;
INSERT INTO `hospital` VALUES (1,'Hospital de la Capital','ciudad','78440698','Activo',1),(2,'Hospital Chiquimulilla','chiqui','34234323',NULL,1),(3,'Hospital Pedro de Alvarado','jutiapa','31240312',NULL,1);
/*!40000 ALTER TABLE `hospital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internado`
--

DROP TABLE IF EXISTS `internado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internado` (
  `idinternado` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_final` datetime DEFAULT NULL,
  `estado` enum('Ingreso','Internado','Salida') NOT NULL DEFAULT 'Ingreso',
  `fecha` datetime DEFAULT NULL,
  `idhabitacion` int(11) NOT NULL DEFAULT '1',
  `idpaciente` int(11) NOT NULL DEFAULT '1',
  `es_operacion` enum('Si','No') NOT NULL DEFAULT 'Si',
  PRIMARY KEY (`idinternado`),
  KEY `fk_internado_idhabitacion_idx` (`idhabitacion`),
  KEY `fk_internado_idpaciente_idx` (`idpaciente`),
  CONSTRAINT `fk_internado_idhabitacion` FOREIGN KEY (`idhabitacion`) REFERENCES `habitacion` (`idhabitacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_internado_idpaciente` FOREIGN KEY (`idpaciente`) REFERENCES `paciente` (`idpaciente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internado`
--

LOCK TABLES `internado` WRITE;
/*!40000 ALTER TABLE `internado` DISABLE KEYS */;
INSERT INTO `internado` VALUES (1,NULL,NULL,'Internado','2015-10-16 00:00:00',1,1,'Si'),(2,NULL,NULL,'Ingreso','2015-05-04 00:00:00',3,2,'Si'),(3,NULL,NULL,'Ingreso','2015-10-12 00:00:00',3,4,'Si'),(4,NULL,NULL,'Ingreso','2015-08-10 00:00:00',1,4,'Si');
/*!40000 ALTER TABLE `internado` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER `nexthor_hospital`.`internado_BEFORE_UPDATE` BEFORE UPDATE ON `internado` FOR EACH ROW
BEGIN
	if old.estado != new.estado then
		if new.estado = 'Internado' then
			set new.fecha_inicio = now();
		end if;
        if new.estado = 'Salida' then
			set new.fecha_final = now();
		end if;
    end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `internado_diario`
--

DROP TABLE IF EXISTS `internado_diario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internado_diario` (
  `idinternado_diario` int(11) NOT NULL AUTO_INCREMENT,
  `idinternado` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `costo` decimal(10,0) NOT NULL DEFAULT '0',
  `idcuenta` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idinternado_diario`),
  KEY `fk_internado_diario_idcuenta_idx` (`idcuenta`),
  KEY `fk_internado_diario_idinternado_idx` (`idinternado`),
  CONSTRAINT `fk_internado_diario_idinternado` FOREIGN KEY (`idinternado`) REFERENCES `internado` (`idinternado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_internado_diario_idcuenta` FOREIGN KEY (`idcuenta`) REFERENCES `cuenta` (`idcuenta`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internado_diario`
--

LOCK TABLES `internado_diario` WRITE;
/*!40000 ALTER TABLE `internado_diario` DISABLE KEYS */;
INSERT INTO `internado_diario` VALUES (1,1,'2015-10-15',11,1);
/*!40000 ALTER TABLE `internado_diario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laboratorio`
--

DROP TABLE IF EXISTS `laboratorio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `laboratorio` (
  `idlaboratorio` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `idpais` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idlaboratorio`),
  KEY `fk_laboratorio_idpais_idx` (`idpais`),
  CONSTRAINT `fk_laboratorio_idpais` FOREIGN KEY (`idpais`) REFERENCES `pais` (`idpais`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laboratorio`
--

LOCK TABLES `laboratorio` WRITE;
/*!40000 ALTER TABLE `laboratorio` DISABLE KEYS */;
INSERT INTO `laboratorio` VALUES (1,'Bayer','Activo',2),(2,'Laprim','Activo',1),(3,'L B C LIMITADA','Activo',2),(4,'Pharma','Activo',1),(5,'Valma','Activo',2),(6,'Prater','Activo',1),(7,'Saval','Activo',1);
/*!40000 ALTER TABLE `laboratorio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicina`
--

DROP TABLE IF EXISTS `medicina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medicina` (
  `idmedicina` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `idlaboratorio` int(11) NOT NULL DEFAULT '1',
  `idhospital` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idmedicina`),
  KEY `fk_medicina_idlaboratorio_idx` (`idlaboratorio`),
  KEY `fk_medicina_idhospita_idx` (`idhospital`),
  CONSTRAINT `fk_medicina_idhospita` FOREIGN KEY (`idhospital`) REFERENCES `hospital` (`idhospital`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_medicina_idlaboratorio` FOREIGN KEY (`idlaboratorio`) REFERENCES `laboratorio` (`idlaboratorio`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicina`
--

LOCK TABLES `medicina` WRITE;
/*!40000 ALTER TABLE `medicina` DISABLE KEYS */;
INSERT INTO `medicina` VALUES (1,'Aspirina','Activo',1,1),(2,'Tapcin','Activo',1,1),(3,'edf','Activo',2,1),(4,'Acetaminofen 1','Activo',1,1),(5,'Acetaminofen 2','Activo',1,1),(6,'Aciclovir (Tópico)','Activo',1,1),(7,'Acido clavulanico','Activo',1,1),(8,'Albendazol','Activo',1,1),(9,'Aldesleukina','Activo',1,1),(10,'Amikacina','Activo',1,1),(11,'Amoxicilina Capsula','Activo',1,1),(12,'Amoxiicilina Suspension','Activo',1,1),(13,'Dicloxacilina','Activo',1,1),(14,'Doripenenm','Activo',1,1),(15,'Gabapentina (Oral)','Activo',1,1),(16,'Ganirelix (inyección)','Activo',1,1),(17,'Gentamicina (Ótica)','Activo',1,1),(18,'Glutetimida (Oral)','Activo',1,1),(19,'Griseofulvina (Oral)','Activo',2,1),(20,'Moxifloxacino','Activo',2,1),(21,'Suero Oral','Activo',2,1),(22,'Teicoplanina','Activo',2,1),(23,'Tretraciclina','Activo',1,1);
/*!40000 ALTER TABLE `medicina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `municipio`
--

DROP TABLE IF EXISTS `municipio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `municipio` (
  `idmunicipio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `iddepartamento` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idmunicipio`),
  KEY `fk_municipio_iddepartamento_idx` (`iddepartamento`),
  CONSTRAINT `fk_municipio_iddepartamento` FOREIGN KEY (`iddepartamento`) REFERENCES `departamento` (`iddepartamento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `municipio`
--

LOCK TABLES `municipio` WRITE;
/*!40000 ALTER TABLE `municipio` DISABLE KEYS */;
INSERT INTO `municipio` VALUES (1,'Guatemala','Activo',1);
/*!40000 ALTER TABLE `municipio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nivel`
--

DROP TABLE IF EXISTS `nivel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nivel` (
  `idnivel` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `idhospital` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idnivel`),
  KEY `fk_nivel_idhospital_idx` (`idhospital`),
  CONSTRAINT `fk_nivel_idhospital` FOREIGN KEY (`idhospital`) REFERENCES `hospital` (`idhospital`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nivel`
--

LOCK TABLES `nivel` WRITE;
/*!40000 ALTER TABLE `nivel` DISABLE KEYS */;
INSERT INTO `nivel` VALUES (1,'primer piso','Activo',1);
/*!40000 ALTER TABLE `nivel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paciente` (
  `idpaciente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) DEFAULT NULL,
  `apellido` varchar(15) DEFAULT NULL,
  `direccion` varchar(15) DEFAULT NULL,
  `cui` varchar(10) DEFAULT NULL,
  `telefono` varchar(8) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idpaciente`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente`
--

LOCK TABLES `paciente` WRITE;
/*!40000 ALTER TABLE `paciente` DISABLE KEYS */;
INSERT INTO `paciente` VALUES (1,'Angelo','Galindo',NULL,'2165479846','7854 456','Activo'),(2,'Javier','Blanco',NULL,'2165479846','7854 456','Activo'),(3,'Estuardo','Blanco',NULL,'2165479846','7854 456','Activo'),(4,'Romeo','Ramirez',NULL,'2165479846','7854 456','Activo'),(5,'Osmar','Muñoz',NULL,NULL,NULL,'Activo');
/*!40000 ALTER TABLE `paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pais`
--

DROP TABLE IF EXISTS `pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pais` (
  `idpais` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `nombre oficial` varchar(45) DEFAULT NULL,
  `gentilicio` varchar(45) DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  `idcontinente` int(11) NOT NULL DEFAULT '1',
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idpais`),
  KEY `fk_pais_idcontinente_idx` (`idcontinente`),
  CONSTRAINT `fk_pais_idcontinente` FOREIGN KEY (`idcontinente`) REFERENCES `continente` (`idcontinente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pais`
--

LOCK TABLES `pais` WRITE;
/*!40000 ALTER TABLE `pais` DISABLE KEYS */;
INSERT INTO `pais` VALUES (1,'Guatemala','República de Guatemala','Guatemalteco',NULL,1,'Activo'),(2,'Alemania','Alemania','Aleman',NULL,2,'Activo');
/*!40000 ALTER TABLE `pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receta`
--

DROP TABLE IF EXISTS `receta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receta` (
  `idreceta` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) NOT NULL DEFAULT '1',
  `idmedicina` int(11) NOT NULL DEFAULT '1',
  `fecha` date DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  `precio_unitario` decimal(10,0) NOT NULL DEFAULT '0',
  `idturno` int(11) NOT NULL DEFAULT '1',
  `idcuenta` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idreceta`),
  KEY `fk_receta_idempleado_idx` (`idempleado`),
  KEY `fk_receta_idmedicina_idx` (`idmedicina`),
  KEY `fk_receta_turno_idx` (`idturno`),
  KEY `fk_receta_idcuenta_idx` (`idcuenta`),
  CONSTRAINT `fk_receta_idcuenta` FOREIGN KEY (`idcuenta`) REFERENCES `cuenta` (`idcuenta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receta_idempleado` FOREIGN KEY (`idempleado`) REFERENCES `empleado` (`idempleado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receta_idmedicina` FOREIGN KEY (`idmedicina`) REFERENCES `medicina` (`idmedicina`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receta_turno` FOREIGN KEY (`idturno`) REFERENCES `turno` (`idturno`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receta`
--

LOCK TABLES `receta` WRITE;
/*!40000 ALTER TABLE `receta` DISABLE KEYS */;
INSERT INTO `receta` VALUES (1,1,1,'2015-02-15',1,50,1,1),(2,1,1,'2015-06-15',1,50,1,1),(3,1,2,'2015-10-15',1,50,1,1),(4,1,3,'2015-01-15',5,50,3,1),(5,1,4,'2015-04-15',1,50,4,3),(6,1,5,'2015-02-15',12,50,2,2),(7,1,11,'2015-10-15',10,50,1,1),(8,1,10,'2015-08-15',81,50,4,1),(9,1,9,'2015-07-15',21,50,3,1),(10,1,8,'2015-09-15',1,50,1,3),(11,1,7,'2015-10-15',12,50,2,1),(12,1,6,'2015-10-15',5,50,1,1);
/*!40000 ALTER TABLE `receta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sala`
--

DROP TABLE IF EXISTS `sala`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sala` (
  `idsala` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `idnivel` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idsala`),
  KEY `fk_sala_idnivel_idx` (`idnivel`),
  CONSTRAINT `fk_sala_idnivel` FOREIGN KEY (`idnivel`) REFERENCES `nivel` (`idnivel`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sala`
--

LOCK TABLES `sala` WRITE;
/*!40000 ALTER TABLE `sala` DISABLE KEYS */;
INSERT INTO `sala` VALUES (1,'Emergencias','Activo',1);
/*!40000 ALTER TABLE `sala` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio_medico`
--

DROP TABLE IF EXISTS `servicio_medico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio_medico` (
  `idservicio_medico` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `es_operacion` enum('Si','No') NOT NULL DEFAULT 'Si',
  PRIMARY KEY (`idservicio_medico`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio_medico`
--

LOCK TABLES `servicio_medico` WRITE;
/*!40000 ALTER TABLE `servicio_medico` DISABLE KEYS */;
INSERT INTO `servicio_medico` VALUES (1,'Operacion de Corazon','Activo','Si'),(2,'Operacion de Pulpones','Activo','Si'),(3,'Vacunacion','Activo','No'),(4,'Cirugía Plastica','Activo','Si'),(5,'Papanicolao','Activo','No');
/*!40000 ALTER TABLE `servicio_medico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio_medico_prestado`
--

DROP TABLE IF EXISTS `servicio_medico_prestado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio_medico_prestado` (
  `idservicio_medico_prestado` int(11) NOT NULL AUTO_INCREMENT,
  `idcuenta` int(11) DEFAULT NULL,
  `estado` enum('Solicitado','Proceso','Terminado') NOT NULL DEFAULT 'Solicitado',
  `costo` decimal(10,0) NOT NULL DEFAULT '0',
  `idservicio_medico` int(11) NOT NULL DEFAULT '1',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  PRIMARY KEY (`idservicio_medico_prestado`),
  KEY `fk_servicio_medico_prestado_idcuenta_idx` (`idcuenta`),
  KEY `fk_servicio_medico_prestado_idservicio_medico_idx` (`idservicio_medico`),
  CONSTRAINT `fk_servicio_medico_prestado_idservicio_medico` FOREIGN KEY (`idservicio_medico`) REFERENCES `servicio_medico` (`idservicio_medico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicio_medico_prestado_idcuenta` FOREIGN KEY (`idcuenta`) REFERENCES `cuenta` (`idcuenta`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio_medico_prestado`
--

LOCK TABLES `servicio_medico_prestado` WRITE;
/*!40000 ALTER TABLE `servicio_medico_prestado` DISABLE KEYS */;
INSERT INTO `servicio_medico_prestado` VALUES (1,4,'Terminado',5000,1,'2015-10-16','2015-10-16'),(2,5,'Solicitado',5000,2,NULL,NULL),(3,7,'Solicitado',45,3,NULL,NULL);
/*!40000 ALTER TABLE `servicio_medico_prestado` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50003 TRIGGER `nexthor_hospital`.`servicio_medico_prestado_BEFORE_UPDATE` BEFORE UPDATE ON `servicio_medico_prestado` FOR EACH ROW
BEGIN
	if old.estado != new.estado then
		if new.estado = 'Proceso' then
			set new.fecha_inicio = now();
		end if;
        if new.estado = 'Terminado' then
			set new.fecha_final = now();
		end if;
    end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `tipo_turno`
--

DROP TABLE IF EXISTS `tipo_turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_turno` (
  `idtipo_turno` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `horario_inicio` varchar(45) DEFAULT NULL,
  `horario_fin` varchar(45) DEFAULT NULL,
  `tipo` enum('Nocturno','Diurno','Mixto') NOT NULL DEFAULT 'Nocturno',
  PRIMARY KEY (`idtipo_turno`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_turno`
--

LOCK TABLES `tipo_turno` WRITE;
/*!40000 ALTER TABLE `tipo_turno` DISABLE KEYS */;
INSERT INTO `tipo_turno` VALUES (1,'Nocturno','18:00','04:00','Nocturno'),(2,'Diurno','8:00','17:00','Diurno');
/*!40000 ALTER TABLE `tipo_turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turno`
--

DROP TABLE IF EXISTS `turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `turno` (
  `idturno` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `idtipo_turno` int(11) NOT NULL DEFAULT '1',
  `idhospital` int(11) NOT NULL DEFAULT '1',
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idturno`),
  KEY `fk_turno_idhospital_idx` (`idhospital`),
  KEY `fk_turno_idtipo_turno_idx` (`idtipo_turno`),
  CONSTRAINT `fk_turno_idtipo_turno` FOREIGN KEY (`idtipo_turno`) REFERENCES `tipo_turno` (`idtipo_turno`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_turno_idhospital` FOREIGN KEY (`idhospital`) REFERENCES `hospital` (`idhospital`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turno`
--

LOCK TABLES `turno` WRITE;
/*!40000 ALTER TABLE `turno` DISABLE KEYS */;
INSERT INTO `turno` VALUES (1,'Tercer turno',1,1,'Activo'),(2,'Segundo turno',2,1,'Activo'),(3,'Primer turno',1,2,'Activo'),(4,'Cuarto turno',2,2,'Activo');
/*!40000 ALTER TABLE `turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'nexthor_hospital'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-10-17  2:46:05
