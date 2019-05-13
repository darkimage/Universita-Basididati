-- MySQL dump 10.13  Distrib 8.0.15, for Win64 (x86_64)
--
-- Host: localhost    Database: taskmgr
-- ------------------------------------------------------
-- Server version	8.0.15

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assignee`
--

DROP TABLE IF EXISTS `assignee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `assignee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User` int(11) DEFAULT NULL,
  `tGroup` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `User` (`User`),
  KEY `tGroup` (`tGroup`),
  CONSTRAINT `assignee_ibfk_1` FOREIGN KEY (`User`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assignee_ibfk_2` FOREIGN KEY (`tGroup`) REFERENCES `tgroup` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignee`
--

LOCK TABLES `assignee` WRITE;
/*!40000 ALTER TABLE `assignee` DISABLE KEYS */;
INSERT INTO `assignee` VALUES (1,3,NULL),(2,2,NULL),(3,3,NULL),(4,NULL,1),(5,NULL,2),(9,3,NULL),(10,3,NULL),(11,4,NULL),(12,NULL,2);
/*!40000 ALTER TABLE `assignee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grouprole`
--

DROP TABLE IF EXISTS `grouprole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `grouprole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Userid` int(11) NOT NULL,
  `Groupid` int(11) NOT NULL,
  `Roleid` int(11) NOT NULL,
  PRIMARY KEY (`Userid`,`Groupid`),
  UNIQUE KEY `id` (`id`),
  KEY `Roleid` (`Roleid`),
  KEY `Userid` (`Userid`),
  KEY `Groupid` (`Groupid`),
  CONSTRAINT `grouprole_ibfk_1` FOREIGN KEY (`Userid`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grouprole_ibfk_2` FOREIGN KEY (`Groupid`) REFERENCES `tgroup` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grouprole_ibfk_3` FOREIGN KEY (`Roleid`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grouprole`
--

LOCK TABLES `grouprole` WRITE;
/*!40000 ALTER TABLE `grouprole` DISABLE KEYS */;
INSERT INTO `grouprole` VALUES (1,1,1,4),(6,1,2,6),(2,2,1,5),(5,3,1,6),(3,3,2,4),(4,4,2,6);
/*!40000 ALTER TABLE `grouprole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(32) NOT NULL,
  `Descrizione` text,
  `Completato` tinyint(1) DEFAULT '0',
  `DataInizio` datetime NOT NULL,
  `DataCompletamento` date DEFAULT NULL,
  `DataScadenza` date NOT NULL,
  `Creatore` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Creatore` (`Creatore`),
  CONSTRAINT `project_ibfk_1` FOREIGN KEY (`Creatore`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (1,'Progetto Completato','Qui viene inserita una accurata descrizione del progetto e non solo.\r\nLa descrizione supporta anche il multilinea.\r\nNel caso un progetto venga segnato come completato non puo tornare ad essre incompleto.\r\nAd ogni progetto possono essere associati dei Gruppi che aggiungono gli\r\nutenti al progetto, e solo il cratore di quest ultimo puo aggiungere Task al progetto e\r\ndichiaralo completato. \r\nSolo dopo la creazione e possibile aggiungere i gruppi.\r\nIl progetto e sempre modificabile e cancellabile anche dopo il completamento.',1,'2019-04-23 00:00:00','2019-05-01','2019-06-21',1),(2,'Progetto creazione sito web','Qui viene inserita una accurata descrizione del progetto e non solo.\r\nLa descrizione supporta anche il multilinea.\r\nNel caso un progetto venga segnato come completato non puo tornare ad essre incompleto.\r\nAd ogni progetto possono essere associati dei Gruppi che aggiungono gli\r\nutenti al progetto, e solo il cratore di quest ultimo puo aggiungere Task al progetto e\r\ndichiaralo completato. \r\nSolo dopo la creazione e possibile aggiungere i gruppi.\r\nIl progetto e sempre modificabile e cancellabile anche dopo il completamento.',0,'2019-04-23 00:00:00',NULL,'2019-07-25',2),(3,'Progetto vuoto','Qui viene inserita una accurata descrizione del progetto e non solo.\r\nLa descrizione supporta anche il multilinea.\r\nNel caso un progetto venga segnato come completato non puo tornare ad essre incompleto.\r\nAd ogni progetto possono essere associati dei Gruppi che aggiungono gli\r\nutenti al progetto, e solo il cratore di quest ultimo puo aggiungere Task al progetto e\r\ndichiaralo completato. \r\nSolo dopo la creazione e possibile aggiungere i gruppi.\r\nIl progetto e sempre modificabile e cancellabile anche dopo il completamento.',0,'2019-05-13 00:00:00',NULL,'2019-08-05',3),(4,'progetto taskmanager','Qui viene inserita una accurata descrizione del progetto e non solo.\r\nLa descrizione supporta anche il multilinea.\r\nNel caso un progetto venga segnato come completato non puo tornare ad essre incompleto.\r\nAd ogni progetto possono essere associati dei Gruppi che aggiungono gli\r\nutenti al progetto, e solo il cratore di quest ultimo puo aggiungere Task al progetto e\r\ndichiaralo completato. \r\nSolo dopo la creazione e possibile aggiungere i gruppi.\r\nIl progetto e sempre modificabile e cancellabile anche dopo il completamento.',0,'2019-05-13 00:00:00',NULL,'2019-05-13',4);
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projectgroup`
--

DROP TABLE IF EXISTS `projectgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `projectgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tGroup` int(11) NOT NULL,
  `Project` int(11) NOT NULL,
  PRIMARY KEY (`tGroup`,`Project`),
  UNIQUE KEY `id` (`id`),
  KEY `Project` (`Project`),
  KEY `tGroup` (`tGroup`),
  CONSTRAINT `projectgroup_ibfk_1` FOREIGN KEY (`tGroup`) REFERENCES `tgroup` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projectgroup_ibfk_2` FOREIGN KEY (`Project`) REFERENCES `project` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectgroup`
--

LOCK TABLES `projectgroup` WRITE;
/*!40000 ALTER TABLE `projectgroup` DISABLE KEYS */;
INSERT INTO `projectgroup` VALUES (2,2,1),(4,2,2),(5,3,2),(29,4,1),(30,1,1),(31,4,4),(32,2,4);
/*!40000 ALTER TABLE `projectgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Authority` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'SUPERADMIN'),(2,'ADMIN'),(3,'USER'),(4,'CREATORE'),(5,'MODERATORE'),(6,'UTENTE');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sharedtask`
--

DROP TABLE IF EXISTS `sharedtask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `sharedtask` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User` int(11) NOT NULL,
  `Task` int(11) NOT NULL,
  PRIMARY KEY (`User`,`Task`),
  UNIQUE KEY `id` (`id`),
  KEY `User` (`User`),
  KEY `Task` (`Task`),
  CONSTRAINT `sharedtask_ibfk_1` FOREIGN KEY (`User`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sharedtask_ibfk_2` FOREIGN KEY (`Task`) REFERENCES `task` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sharedtask`
--

LOCK TABLES `sharedtask` WRITE;
/*!40000 ALTER TABLE `sharedtask` DISABLE KEYS */;
INSERT INTO `sharedtask` VALUES (1,1,3),(9,2,1),(10,2,10);
/*!40000 ALTER TABLE `sharedtask` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(32) NOT NULL,
  `Descrizione` text NOT NULL,
  `DataCreazione` date NOT NULL,
  `DataScadenza` date NOT NULL,
  `DataCompletamento` date DEFAULT NULL,
  `Completata` tinyint(1) NOT NULL,
  `User` int(11) NOT NULL,
  `Project` int(11) NOT NULL,
  `Assignee` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `User` (`User`),
  KEY `Project` (`Project`),
  KEY `Assignee` (`Assignee`),
  CONSTRAINT `task_ibfk_1` FOREIGN KEY (`User`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_ibfk_2` FOREIGN KEY (`Project`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_ibfk_3` FOREIGN KEY (`Assignee`) REFERENCES `assignee` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task`
--

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` VALUES (1,'Completata con dipendenze','Le Task sono compiti assegnati a specifici progetti ed \r\na specifiche persono o gruppi di utenti.\r\nLe Task possono essere ulteriormente condivise con singoli utenti\r\nche possono anche non partecipare al progetto del quale la task\r\noggetto di condivisione fa parte.\r\nCome i progetti le task hanno un inizio e una data di scadenza\r\ne possono essere dichiarate completate.\r\nLe Task possono anche avere dipendere da una lista di altre task che \r\nfanno parte del progetto.','2019-05-04','2019-05-31',NULL,1,1,1,5),(2,'Completata dipendenza 1','Le Task sono compiti assegnati a specifici progetti ed \r\na specifiche persono o gruppi di utenti.\r\nLe Task possono essere ulteriormente condivise con singoli utenti\r\nche possono anche non partecipare al progetto del quale la task\r\noggetto di condivisione fa parte.\r\nCome i progetti le task hanno un inizio e una data di scadenza\r\ne possono essere dichiarate completate.\r\nLe Task possono anche avere dipendere da una lista di altre task che \r\nfanno parte del progetto.','2019-05-04','2019-05-31',NULL,1,2,1,2),(3,'Completata dipendenza 2','Le Task sono compiti assegnati a specifici progetti ed \r\na specifiche persono o gruppi di utenti.\r\nLe Task possono essere ulteriormente condivise con singoli utenti\r\nche possono anche non partecipare al progetto del quale la task\r\noggetto di condivisione fa parte.\r\nCome i progetti le task hanno un inizio e una data di scadenza\r\ne possono essere dichiarate completate.\r\nLe Task possono anche avere dipendere da una lista di altre task che \r\nfanno parte del progetto.','2019-05-04','2019-05-31',NULL,1,2,1,1),(6,'creare il sito web','Le Task sono compiti assegnati a specifici progetti ed \r\na specifiche persono o gruppi di utenti.\r\nLe Task possono essere ulteriormente condivise con singoli utenti\r\nche possono anche non partecipare al progetto del quale la task\r\noggetto di condivisione fa parte.\r\nCome i progetti le task hanno un inizio e una data di scadenza\r\ne possono essere dichiarate completate.\r\nLe Task possono anche avere dipendere da una lista di altre task che \r\nfanno parte del progetto.','2019-05-05','2019-05-05',NULL,0,1,2,4),(7,'interfaccia responsiva Bootstrap','Le Task sono compiti assegnati a specifici progetti ed \r\na specifiche persono o gruppi di utenti.\r\nLe Task possono essere ulteriormente condivise con singoli utenti\r\nche possono anche non partecipare al progetto del quale la task\r\noggetto di condivisione fa parte.\r\nCome i progetti le task hanno un inizio e una data di scadenza\r\ne possono essere dichiarate completate.\r\nLe Task possono anche avere dipendere da una lista di altre task che \r\nfanno parte del progetto.','2019-05-13','2019-05-13',NULL,0,1,2,10),(9,'creare API per javascript','Le Task sono compiti assegnati a specifici progetti ed \r\na specifiche persono o gruppi di utenti.\r\nLe Task possono essere ulteriormente condivise con singoli utenti\r\nche possono anche non partecipare al progetto del quale la task\r\noggetto di condivisione fa parte.\r\nCome i progetti le task hanno un inizio e una data di scadenza\r\ne possono essere dichiarate completate.\r\nLe Task possono anche avere dipendere da una lista di altre task che \r\nfanno parte del progetto.\r\n','2019-05-13','2019-05-16',NULL,0,1,2,11),(10,'ultimare relazione progetto','Le Task sono compiti assegnati a specifici progetti ed \r\na specifiche persono o gruppi di utenti.\r\nLe Task possono essere ulteriormente condivise con singoli utenti\r\nche possono anche non partecipare al progetto del quale la task\r\noggetto di condivisione fa parte.\r\nCome i progetti le task hanno un inizio e una data di scadenza\r\ne possono essere dichiarate completate.\r\nLe Task possono anche avere dipendere da una lista di altre task che \r\nfanno parte del progetto.','2019-05-13','2019-04-28','2019-05-13',1,4,4,12);
/*!40000 ALTER TABLE `task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tasklist`
--

DROP TABLE IF EXISTS `tasklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tasklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Task` int(11) NOT NULL,
  `Completata` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Task` (`Task`),
  CONSTRAINT `tasklist_ibfk_1` FOREIGN KEY (`Task`) REFERENCES `task` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasklist`
--

LOCK TABLES `tasklist` WRITE;
/*!40000 ALTER TABLE `tasklist` DISABLE KEYS */;
INSERT INTO `tasklist` VALUES (39,1,0),(40,6,0);
/*!40000 ALTER TABLE `tasklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tgroup`
--

DROP TABLE IF EXISTS `tgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tgroup`
--

LOCK TABLES `tgroup` WRITE;
/*!40000 ALTER TABLE `tgroup` DISABLE KEYS */;
INSERT INTO `tgroup` VALUES (1,'Gruppo1'),(2,'Gruppo2'),(3,'Gruppo3'),(4,'Nuovo Gruppo');
/*!40000 ALTER TABLE `tgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tlist`
--

DROP TABLE IF EXISTS `tlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Task` int(11) NOT NULL,
  `TaskList` int(11) NOT NULL,
  PRIMARY KEY (`Task`,`TaskList`),
  KEY `Task` (`Task`),
  KEY `TaskList` (`TaskList`),
  UNIQUE KEY `id` (`id`),
  CONSTRAINT `tlist_ibfk_1` FOREIGN KEY (`Task`) REFERENCES `task` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tlist_ibfk_2` FOREIGN KEY (`TaskList`) REFERENCES `tasklist` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tlist`
--

LOCK TABLES `tlist` WRITE;
/*!40000 ALTER TABLE `tlist` DISABLE KEYS */;
INSERT INTO `tlist` VALUES (21,2,39),(22,3,39),(23,9,40),(24,7,40);
/*!40000 ALTER TABLE `tlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(32) NOT NULL,
  `Cognome` varchar(32) NOT NULL,
  `DataNascita` date NOT NULL,
  `NomeUtente` varchar(32) NOT NULL,
  `Password` char(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Luca','Faggion','1995-12-04','lucafaggion','$2y$10$4K7U5eQDrsWkdP3B3qYQounKJG/UDAsY/VtAPqbv4aD3nsZoZd0CK'),(2,'Giacomo','Bianchi','1962-08-01','giacomobianchi','$2y$10$4K7U5eQDrsWkdP3B3qYQounKJG/UDAsY/VtAPqbv4aD3nsZoZd0CK'),(3,'Marco','Rossi','1972-07-22','marcorossi','$2y$10$4K7U5eQDrsWkdP3B3qYQounKJG/UDAsY/VtAPqbv4aD3nsZoZd0CK'),(4,'Andrea','Marchi','2000-03-09','andreamarchi','$2y$10$4K7U5eQDrsWkdP3B3qYQounKJG/UDAsY/VtAPqbv4aD3nsZoZd0CK');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userrole`
--

DROP TABLE IF EXISTS `userrole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `userrole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Userid` int(11) NOT NULL,
  `Roleid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Userid` (`Userid`),
  KEY `Roleid` (`Roleid`),
  CONSTRAINT `userrole_ibfk_1` FOREIGN KEY (`Userid`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `userrole_ibfk_2` FOREIGN KEY (`Roleid`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userrole`
--

LOCK TABLES `userrole` WRITE;
/*!40000 ALTER TABLE `userrole` DISABLE KEYS */;
INSERT INTO `userrole` VALUES (1,1,1),(2,2,3),(3,3,3),(4,4,2);
/*!40000 ALTER TABLE `userrole` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-13 13:40:07
