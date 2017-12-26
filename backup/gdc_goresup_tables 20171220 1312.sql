﻿--
-- Script was generated by Devart dbForge Studio for MySQL, Version 7.2.34.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 20/12/2017 13:12:55
-- Server version: 5.5.5-10.1.28-MariaDB
-- Client version: 4.1
--


-- 
-- Disable foreign keys
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Set SQL mode
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Set character set the client will use to send SQL statements to the server
--
SET NAMES 'utf8';

-- 
-- Set default database
--
USE gdc_goresup;

--
-- Definition for table cod_cont
--
DROP TABLE IF EXISTS cod_cont;
CREATE TABLE cod_cont (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  last_doc VARCHAR(10) NOT NULL,
  last_exp VARCHAR(10) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 2
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table migrations
--
DROP TABLE IF EXISTS migrations;
CREATE TABLE migrations (
  migration VARCHAR(255) NOT NULL,
  batch INT(11) NOT NULL
)
ENGINE = INNODB
AVG_ROW_LENGTH = 1489
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramarchivador
--
DROP TABLE IF EXISTS tramarchivador;
CREATE TABLE tramarchivador (
  tarcId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  tarcExp VARCHAR(10) NOT NULL,
  tarcDatePres DATE NOT NULL,
  tarcStatus VARCHAR(15) NOT NULL,
  created_at DATE NOT NULL,
  created_time_at TIME NOT NULL,
  updated_at DATE DEFAULT NULL,
  tarcSource VARCHAR(3) NOT NULL,
  tarcPathFile VARCHAR(500) DEFAULT NULL,
  tarcYear INT(10) UNSIGNED DEFAULT NULL,
  tarcAsoc INT(10) UNSIGNED DEFAULT NULL,
  tarcTitulo VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (tarcId)
)
ENGINE = INNODB
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramdependencia
--
DROP TABLE IF EXISTS tramdependencia;
CREATE TABLE tramdependencia (
  depId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  depCod VARCHAR(12) DEFAULT NULL,
  depDsc VARCHAR(1000) DEFAULT NULL,
  depDscC VARCHAR(1000) DEFAULT NULL,
  depActive TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (depId)
)
ENGINE = INNODB
AUTO_INCREMENT = 2
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table trampersona
--
DROP TABLE IF EXISTS trampersona;
CREATE TABLE trampersona (
  tprId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  tprDni VARCHAR(8) DEFAULT NULL,
  tprFulName VARCHAR(500) DEFAULT NULL,
  tprPaterno VARCHAR(50) DEFAULT NULL,
  tprMaterno VARCHAR(50) DEFAULT NULL,
  tprNombres VARCHAR(150) DEFAULT NULL,
  tprEntidad VARCHAR(50) DEFAULT NULL,
  tprCargo VARCHAR(50) DEFAULT NULL,
  tprCelular VARCHAR(15) DEFAULT NULL,
  tprCorreo VARCHAR(50) DEFAULT NULL,
  tprRegisterBy VARCHAR(50) DEFAULT NULL,
  tprRegisterAt DATETIME DEFAULT NULL,
  tprUpdatedAt DATETIME DEFAULT NULL,
  PRIMARY KEY (tprId)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramproyecto
--
DROP TABLE IF EXISTS tramproyecto;
CREATE TABLE tramproyecto (
  tpyId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  tpyNro INT(10) UNSIGNED DEFAULT NULL,
  tpyAnio INT(10) UNSIGNED DEFAULT NULL,
  tpyName VARCHAR(500) DEFAULT NULL,
  tpyShortName VARCHAR(500) DEFAULT NULL,
  tpyCU VARCHAR(12) DEFAULT NULL,
  tpyCadena VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (tpyId)
)
ENGINE = INNODB
AUTO_INCREMENT = 2
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramsistema
--
DROP TABLE IF EXISTS tramsistema;
CREATE TABLE tramsistema (
  tsysId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  tsysModulo VARCHAR(50) DEFAULT NULL,
  tsysFunction VARCHAR(50) NOT NULL,
  tsysDescF VARCHAR(250) NOT NULL,
  tsysVarHandler VARCHAR(50) NOT NULL,
  PRIMARY KEY (tsysId)
)
ENGINE = INNODB
AUTO_INCREMENT = 13
AVG_ROW_LENGTH = 1489
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramtipodocumento
--
DROP TABLE IF EXISTS tramtipodocumento;
CREATE TABLE tramtipodocumento (
  ttypDoc VARCHAR(5) NOT NULL,
  ttypDesc VARCHAR(100) NOT NULL,
  created_at DATE DEFAULT NULL,
  updated_at DATE DEFAULT NULL,
  ttypShow TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (ttypDoc)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 1820
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramusuario
--
DROP TABLE IF EXISTS tramusuario;
CREATE TABLE tramusuario (
  tusId VARCHAR(8) NOT NULL,
  tusNickName VARCHAR(20) NOT NULL,
  password VARCHAR(60) NOT NULL,
  tusNames VARCHAR(100) NOT NULL,
  tusPaterno VARCHAR(100) NOT NULL,
  tusMaterno VARCHAR(100) NOT NULL,
  tusWorkDep VARCHAR(12) NOT NULL,
  tusTypeUser VARCHAR(50) NOT NULL,
  tusRegisterBy VARCHAR(50) DEFAULT NULL,
  tusRegisterAt DATETIME DEFAULT NULL,
  tusState TINYINT(1) DEFAULT NULL,
  PRIMARY KEY (tusId)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramdocumento
--
DROP TABLE IF EXISTS tramdocumento;
CREATE TABLE tramdocumento (
  tdocId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  tdocCod VARCHAR(10) NOT NULL,
  tdocExp INT(10) UNSIGNED NOT NULL,
  tdocExp1 VARCHAR(10) DEFAULT NULL,
  tdocDependencia VARCHAR(100) DEFAULT NULL,
  tdocProject INT(10) UNSIGNED NOT NULL,
  tdocSender VARCHAR(500) DEFAULT NULL,
  tdocSenderName VARCHAR(100) DEFAULT NULL,
  tdocSenderPaterno VARCHAR(100) DEFAULT NULL,
  tdocSenderMaterno VARCHAR(100) DEFAULT NULL,
  tdocDni INT(11) DEFAULT NULL,
  tdocJobSender VARCHAR(100) DEFAULT NULL,
  tdocType VARCHAR(5) NOT NULL,
  tdocNumber VARCHAR(200) DEFAULT NULL,
  tdocRegistro VARCHAR(10) DEFAULT NULL,
  tdocDate DATE NOT NULL,
  tdocFolio INT(11) DEFAULT NULL,
  tdocSubject VARCHAR(250) NOT NULL,
  tdocStatus VARCHAR(15) DEFAULT NULL,
  tdocRef VARCHAR(250) DEFAULT NULL,
  tdocDetail VARCHAR(500) DEFAULT NULL,
  tdocAccion VARCHAR(50) DEFAULT NULL,
  tdocFileName VARCHAR(250) DEFAULT NULL,
  tdocFileExt VARCHAR(50) DEFAULT NULL,
  tdocPathFile VARCHAR(500) DEFAULT NULL,
  tdocFileMime VARCHAR(150) DEFAULT NULL,
  tdocRegisterBy VARCHAR(8) NOT NULL,
  tdocRegisterAt DATETIME DEFAULT NULL,
  tdocUpdateAt DATETIME DEFAULT NULL,
  PRIMARY KEY (tdocId),
  CONSTRAINT tramdocumento_tdocexp_foreign FOREIGN KEY (tdocExp)
    REFERENCES tramarchivador(tarcId) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT tramdocumento_tdocproject_foreign FOREIGN KEY (tdocProject)
    REFERENCES tramproyecto(tpyId) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT tramdocumento_tdoctype_foreign FOREIGN KEY (tdocType)
    REFERENCES tramtipodocumento(ttypDoc) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 4096
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramroles
--
DROP TABLE IF EXISTS tramroles;
CREATE TABLE tramroles (
  trolId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  trolIdUser VARCHAR(8) NOT NULL,
  trolIdSyst INT(10) UNSIGNED NOT NULL,
  trolEnable TINYINT(1) NOT NULL,
  PRIMARY KEY (trolId),
  CONSTRAINT tramroles_trolidsyst_foreign FOREIGN KEY (trolIdSyst)
    REFERENCES tramsistema(tsysId) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT tramroles_troliduser_foreign FOREIGN KEY (trolIdUser)
    REFERENCES tramusuario(tusId) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 12
AVG_ROW_LENGTH = 1489
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

--
-- Definition for table tramhistorial
--
DROP TABLE IF EXISTS tramhistorial;
CREATE TABLE tramhistorial (
  thisId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  thisExp VARCHAR(10) NOT NULL,
  thisDoc INT(10) UNSIGNED NOT NULL,
  thisDoc1 VARCHAR(10) DEFAULT NULL,
  thisDepS VARCHAR(12) NOT NULL,
  thisDepT VARCHAR(12) NOT NULL,
  thisFlagR TINYINT(1) NOT NULL,
  thisFlagA TINYINT(1) NOT NULL,
  thisFlagD TINYINT(1) NOT NULL,
  rec_date_at DATE NOT NULL,
  rec_time_at TIME NOT NULL,
  thisDateTimeR DATETIME DEFAULT NULL,
  thisDateTimeA DATETIME DEFAULT NULL,
  thisDateTimeD DATETIME DEFAULT NULL,
  thisDscD VARCHAR(1000) DEFAULT NULL,
  thisDscA VARCHAR(1000) DEFAULT NULL,
  thisIdRef INT(10) UNSIGNED DEFAULT NULL,
  thisDocD VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (thisId),
  CONSTRAINT tramhistorial_thisdoc_foreign FOREIGN KEY (thisDoc)
    REFERENCES tramdocumento(tdocId) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 4096
CHARACTER SET utf8
COLLATE utf8_spanish_ci;

-- 
-- Dumping data for table cod_cont
--
INSERT INTO cod_cont VALUES
(1, 'DOC1700005', 'EXP1700003');

-- 
-- Dumping data for table migrations
--
INSERT INTO migrations VALUES
('2014_10_12_000000_create_users_table', 1),
('2017_12_18_124959_create_sistema_table', 1),
('2017_12_18_125406_create_roles_table', 1),
('2017_12_18_125913_create_table_proyecto', 1),
('2017_12_18_141550_create_tipodocumento_table', 1),
('2017_12_18_142103_create_archivador_table', 1),
('2017_12_18_145117_create_documento_table', 1),
('2017_12_18_150413_create_historial_table', 1),
('2017_12_18_151116_create_persona_table', 1),
('2017_12_18_151646_create_dependencia_table', 1),
('2017_12_18_151937_create_contador_table', 1);

-- 
-- Dumping data for table tramarchivador
--
INSERT INTO tramarchivador VALUES
(1, 'EXP1700001', '2017-12-19', 'procesando', '2017-12-19', '22:39:7', '2017-12-19', 'int', NULL, 2017, 1, 'primerooo'),
(2, 'EXP1700002', '2017-12-20', 'aperturado', '2017-12-20', '6:6:3', '2017-12-20', 'int', NULL, 2017, 1, 'Exp2'),
(3, 'EXP1700003', '2017-12-20', 'procesando', '2017-12-20', '6:10:48', '2017-12-20', 'int', NULL, 2017, 1, 'exp3');

-- 
-- Dumping data for table tramdependencia
--
INSERT INTO tramdependencia VALUES
(1, 'DEP00001', 'OFICINA REGIONAL DE SUPERVISIÓN Y LIQUIDACIÓN DE PROYECTOS', 'Oficina de Supervisión', 1);

-- 
-- Dumping data for table trampersona
--

-- Table gdc_goresup.trampersona does not contain any data (it is empty)

-- 
-- Dumping data for table tramproyecto
--
INSERT INTO tramproyecto VALUES
(1, NULL, 2017, 'Proyecto de nombre', 'Corto proytecto', '20154', NULL);

-- 
-- Dumping data for table tramsistema
--
INSERT INTO tramsistema VALUES
(1, 'gestion', 'registrar', 'Registrar Documento', 'gregistrar'),
(2, 'gestion', 'editar', 'Editar Documento', 'geditar'),
(3, 'gestion', 'eliminar', 'Eliminar Documento', 'geliminar'),
(4, 'gestion', 'derivar', 'Derivar Documento', 'gderivar'),
(5, 'gestion', 'menu', 'Menu de Gestión Documentaria', 'gmenu'),
(6, 'bandeja', 'busqueda', 'Busqueda de Documentos', 'bbusqueda'),
(7, 'bandeja', 'menu', 'Menu de Bandeja de Documentos', 'bmenu'),
(9, 'reporte', 'menu', 'Menu de Resportes', 'rmenu'),
(10, 'reporte', 'ver', 'Visualizar Reportes', 'rver'),
(11, 'reporte', 'pdf', 'Visualizar Reportes en PDF', 'rpdf'),
(12, 'configuracion', 'menu', 'Menu de Configuración del Sistema', 'cmenu');

-- 
-- Dumping data for table tramtipodocumento
--
INSERT INTO tramtipodocumento VALUES
('CAR', 'Carta', NULL, NULL, 1),
('EXT', 'Expediente', NULL, NULL, 1),
('INF', 'Informe', NULL, NULL, 1),
('MEM', 'Memorandum', NULL, NULL, 1),
('MMM', 'Memorandum múltiple', NULL, NULL, 1),
('OFC', 'Oficio circular', NULL, NULL, 1),
('OFI', 'Oficio', NULL, NULL, 1),
('PLT', 'Plan de trabajo', NULL, NULL, 1),
('SOL', 'Solicitud', NULL, NULL, 1);

-- 
-- Dumping data for table tramusuario
--
INSERT INTO tramusuario VALUES
('00000000', 'admin', '$2y$10$4.n8PynPl3J63T.DhBU3o.jQbo1/6PgYWwoXELp72I.XTgweseMai', 'Usuario', 'Administrador', 'Sistema', '1', 'admin', 'admin', '0000-00-00 00:00:00', 1);

-- 
-- Dumping data for table tramdocumento
--
INSERT INTO tramdocumento VALUES
(1, 'DOC1700002', 1, 'EXP1700001', '1', 1, 'aaaaa', NULL, NULL, NULL, 0, 'ssss', 'CAR', '23', '12', '2017-12-19', 22, 'sssssss', 'derivado', NULL, 'dddddd', 'ingreso', NULL, NULL, NULL, NULL, '00000000', NULL, NULL),
(2, 'DOC1700003', 1, 'EXP1700001', '1', 1, 'ddddd', NULL, NULL, NULL, 0, 'asdas', 'CAR', '32', '1', '2017-12-19', 22, 'sadad', 'registrado', '1', 'asdas', 'respuesta', NULL, NULL, NULL, NULL, '00000000', NULL, NULL),
(3, 'DOC1700004', 2, 'EXP1700002', '1', 1, 'ana banana', NULL, NULL, NULL, 0, 'asdss', 'CAR', '23', '15', '2017-12-20', 2, 'sadasdasd', 'registrado', NULL, 'asdasdasd', 'ingreso', NULL, NULL, NULL, NULL, '00000000', NULL, NULL),
(4, 'DOC1700005', 3, 'EXP1700003', '1', 1, 'AAAA', NULL, NULL, NULL, 0, 'SSSS', 'CAR', '222', '16', '2017-12-20', 12, 'aasdasd', 'derivado', NULL, 'asdasd', 'ingreso', NULL, NULL, NULL, NULL, '00000000', NULL, NULL);

-- 
-- Dumping data for table tramroles
--
INSERT INTO tramroles VALUES
(1, '00000000', 1, 1),
(2, '00000000', 2, 1),
(3, '00000000', 3, 1),
(4, '00000000', 4, 1),
(5, '00000000', 5, 1),
(6, '00000000', 6, 1),
(7, '00000000', 7, 1),
(8, '00000000', 9, 1),
(9, '00000000', 10, 1),
(10, '00000000', 11, 1),
(11, '00000000', 12, 1);

-- 
-- Dumping data for table tramhistorial
--
INSERT INTO tramhistorial VALUES
(1, 'EXP1700001', 1, 'DOC1700002', '00000000', '1-2', 1, 0, 1, '2017-12-19', '22:39:23', '0000-00-00 00:00:00', NULL, '0000-00-00 00:00:00', 'ffffff', NULL, 2, NULL),
(2, 'EXP1700001', 2, 'DOC1700003', '00000000', '00000000', 1, 0, 0, '2017-12-19', '22:40:47', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'EXP1700002', 3, 'DOC1700004', '00000000', '00000000', 1, 0, 0, '2017-12-20', '6:6:3', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'EXP1700003', 4, 'DOC1700005', '00000000', '1-2', 1, 0, 1, '2017-12-20', '6:30:23', '2017-12-20 06:10:48', NULL, '2017-12-20 06:30:23', 'aaaaa', NULL, NULL, NULL);

-- 
-- Restore previous SQL mode
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Enable foreign keys
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;