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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignee`
--

LOCK TABLES `assignee` WRITE; 
/*!40000 ALTER TABLE `assignee` DISABLE KEYS */;
INSERT INTO `assignee` VALUES (1,3,NULL),(2,2,NULL),(3,3,NULL),(4,NULL,1),(5,NULL,2);
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
INSERT INTO `grouprole` VALUES (1,1,1,4),(2,2,1,5),(3,3,2,4),(4,4,2,6),(5,3,1,6),(6,1,2,6);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
INSERT INTO `project` VALUES (1,'Progetto di Prova 1','Qui viene inserita una accurata descrizione del progetto e non solo.\r\nsupporta anche il multilinea.\r\nProva\r\nProva.',1,'2019-04-23 00:00:00','2019-05-01','2019-06-21',1),(2,'Progetto 2','Qui viene inserita una accurata descrizione del progetto 2',0,'2019-04-23 00:00:00',NULL,'2019-07-25',2);
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectgroup`
--

LOCK TABLES `projectgroup` WRITE;
/*!40000 ALTER TABLE `projectgroup` DISABLE KEYS */;
INSERT INTO `projectgroup` VALUES (2,2,1),(4,2,2),(5,3,2),(29,4,1),(30,1,1);
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
  KEY `User` (`User`),
  KEY `Task` (`Task`),
  UNIQUE KEY `id` (`id`),
  CONSTRAINT `sharedtask_ibfk_1` FOREIGN KEY (`User`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sharedtask_ibfk_2` FOREIGN KEY (`Task`) REFERENCES `task` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sharedtask`
--

LOCK TABLES `sharedtask` WRITE;
/*!40000 ALTER TABLE `sharedtask` DISABLE KEYS */;
INSERT INTO `sharedtask` VALUES (1,1,3);
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
  `Assignee` int(11),
  PRIMARY KEY (`id`),
  KEY `User` (`User`),
  KEY `Project` (`Project`),
  KEY `Assignee` (`Assignee`),
  CONSTRAINT `task_ibfk_1` FOREIGN KEY (`User`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_ibfk_2` FOREIGN KEY (`Project`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_ibfk_3` FOREIGN KEY (`Assignee`) REFERENCES `assignee` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task`
--

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` VALUES (1,'Prova Task 1 with edit','Descrizione task 1','2019-05-04','2019-05-31','2019-05-05',1,1,1,5),(2,'Prova Task 2','Descrizione task 2','2019-05-04','2019-05-31','2019-05-31',1,2,1,2),(3,'Prova Task 2','Descrizione task 2','2019-05-04','2019-05-31','2019-05-31',1,2,1,1),(6,'Prova task proj 2','Descrivo la task','2019-05-05','2019-05-05',NULL,0,1,2,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tasklist`
--

LOCK TABLES `tasklist` WRITE;
/*!40000 ALTER TABLE `tasklist` DISABLE KEYS */;
INSERT INTO `tasklist` VALUES (38,1,0);
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
  PRIMARY KEY (`id`),
  KEY `Task` (`Task`),
  KEY `TaskList` (`TaskList`),
  CONSTRAINT `tlist_ibfk_1` FOREIGN KEY (`Task`) REFERENCES `task` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tlist_ibfk_2` FOREIGN KEY (`TaskList`) REFERENCES `tasklist` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tlist`
--

LOCK TABLES `tlist` WRITE;
/*!40000 ALTER TABLE `tlist` DISABLE KEYS */;
INSERT INTO `tlist` VALUES (16,3,38),(17,3,38);
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

-- Dump completed on 2019-05-06 12:12:39
