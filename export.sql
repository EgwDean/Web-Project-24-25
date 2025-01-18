-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: diplomatiki_support
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `anathesi_diplomatikis`
--

DROP TABLE IF EXISTS `anathesi_diplomatikis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anathesi_diplomatikis` (
  `email_stud` varchar(255) NOT NULL,
  `id_diploma` int NOT NULL,
  `status` enum('pending','active','canceled_by_student','canceled_by_professor','recalled','under examination','finished') NOT NULL DEFAULT 'pending',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `Nemertes_link` varchar(255) DEFAULT NULL,
  `pdf_main_diploma` varchar(255) DEFAULT NULL,
  `external_links` text,
  `protocol_number` int DEFAULT NULL,
  KEY `ANASTUD` (`email_stud`),
  KEY `ANADIPL` (`id_diploma`),
  KEY `idx_email_status_start_date` (`status`,`start_date`),
  CONSTRAINT `ANADIPL` FOREIGN KEY (`id_diploma`) REFERENCES `diplomatiki` (`id_diplomatiki`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ANASTUD` FOREIGN KEY (`email_stud`) REFERENCES `student` (`email_student`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anathesi_diplomatikis`
--

LOCK TABLES `anathesi_diplomatikis` WRITE;
/*!40000 ALTER TABLE `anathesi_diplomatikis` DISABLE KEYS */;
/*!40000 ALTER TABLE `anathesi_diplomatikis` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `log_insert_anathesi` AFTER INSERT ON `anathesi_diplomatikis` FOR EACH ROW BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  INSERT INTO log (id_di, record)
  VALUES (NEW.id_diploma, CONCAT(
    curr_date, ' - New thesis assigned. Status : pending.'
  ));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `log_update_anathesi` AFTER UPDATE ON `anathesi_diplomatikis` FOR EACH ROW BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  IF (OLD.status != NEW.status ) THEN
  INSERT INTO log (id_di, record)
  VALUES (NEW.id_diploma, CONCAT(
    curr_date, ' - UPDATE: ',
    'Old status: ', IFNULL(OLD.status, 'NULL'), ', ', 'New status: ', IFNULL(NEW.status, 'NULL'), '.'
  ));
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `log_delete_anathesi` AFTER DELETE ON `anathesi_diplomatikis` FOR EACH ROW BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  INSERT INTO log (id_di, record)
  VALUES (OLD.id_diploma, CONCAT(
    curr_date, ' - DELETED: '
  ));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cancellations`
--

DROP TABLE IF EXISTS `cancellations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cancellations` (
  `id_d` int NOT NULL,
  `meeting_number` int NOT NULL,
  `meeting_year` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cancellations`
--

LOCK TABLES `cancellations` WRITE;
/*!40000 ALTER TABLE `cancellations` DISABLE KEYS */;
/*!40000 ALTER TABLE `cancellations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diplomatiki`
--

DROP TABLE IF EXISTS `diplomatiki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diplomatiki` (
  `id_diplomatiki` int NOT NULL AUTO_INCREMENT,
  `email_prof` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `pdf_link_topic` varchar(255) DEFAULT NULL,
  `status` enum('available','given') NOT NULL,
  PRIMARY KEY (`id_diplomatiki`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diplomatiki`
--

LOCK TABLES `diplomatiki` WRITE;
/*!40000 ALTER TABLE `diplomatiki` DISABLE KEYS */;
/*!40000 ALTER TABLE `diplomatiki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eksetasi_diplomatikis`
--

DROP TABLE IF EXISTS `eksetasi_diplomatikis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eksetasi_diplomatikis` (
  `id_diplomatikis` int NOT NULL,
  `email_st` varchar(255) NOT NULL,
  `exam_date` datetime DEFAULT NULL,
  `exam_room` varchar(255) DEFAULT NULL,
  `grade1` decimal(4,2) DEFAULT NULL,
  `grade2` decimal(4,2) DEFAULT NULL,
  `grade3` decimal(4,2) DEFAULT NULL,
  `final_grade` decimal(4,2) DEFAULT NULL,
  `praktiko_bathmologisis` varchar(255) DEFAULT NULL,
  KEY `EXAMDIPL` (`id_diplomatikis`),
  KEY `EXAMSTUD` (`email_st`),
  KEY `idx_email_id_exam_date` (`exam_date`),
  CONSTRAINT `EXAMDIPL` FOREIGN KEY (`id_diplomatikis`) REFERENCES `diplomatiki` (`id_diplomatiki`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `EXAMSTUD` FOREIGN KEY (`email_st`) REFERENCES `student` (`email_student`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eksetasi_diplomatikis`
--

LOCK TABLES `eksetasi_diplomatikis` WRITE;
/*!40000 ALTER TABLE `eksetasi_diplomatikis` DISABLE KEYS */;
/*!40000 ALTER TABLE `eksetasi_diplomatikis` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_final_grade` BEFORE UPDATE ON `eksetasi_diplomatikis` FOR EACH ROW BEGIN
  -- Recalculate the final grade, treating NULL as 0
	  IF (NEW.grade1 IS NOT NULL AND NEW.grade2 IS NOT NULL AND NEW.grade3 IS NOT NULL) THEN 
 		 SET NEW.final_grade = (IFNULL(NEW.grade1, 0) + IFNULL(NEW.grade2, 0) + IFNULL(NEW.grade3, 0)) / 3;
  	  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log` (
  `id_di` int NOT NULL,
  `record` text NOT NULL,
  KEY `idx_id_di` (`id_di`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professor`
--

DROP TABLE IF EXISTS `professor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professor` (
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email_professor` varchar(255) NOT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `landline` varchar(13) DEFAULT NULL,
  `mobile` varchar(13) NOT NULL,
  `department` varchar(255) NOT NULL,
  `university` varchar(255) NOT NULL,
  PRIMARY KEY (`email_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professor`
--

LOCK TABLES `professor` WRITE;
/*!40000 ALTER TABLE `professor` DISABLE KEYS */;
/*!40000 ALTER TABLE `professor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `professor_notes`
--

DROP TABLE IF EXISTS `professor_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professor_notes` (
  `professor_email` varchar(255) NOT NULL,
  `id_diplom` int NOT NULL,
  `notes` text NOT NULL,
  KEY `NOTEPROF` (`professor_email`),
  KEY `NOTEDIPL` (`id_diplom`),
  CONSTRAINT `NOTEDIPL` FOREIGN KEY (`id_diplom`) REFERENCES `diplomatiki` (`id_diplomatiki`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `NOTEPROF` FOREIGN KEY (`professor_email`) REFERENCES `professor` (`email_professor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `professor_notes`
--

LOCK TABLES `professor_notes` WRITE;
/*!40000 ALTER TABLE `professor_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `professor_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prosklisi_se_trimeli`
--

DROP TABLE IF EXISTS `prosklisi_se_trimeli`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prosklisi_se_trimeli` (
  `student_email` varchar(255) NOT NULL,
  `prof_email` varchar(255) NOT NULL,
  `id_dip` int NOT NULL,
  `status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
  `reply_date` date DEFAULT NULL,
  `invitation_date` date DEFAULT NULL,
  KEY `student_email` (`student_email`),
  KEY `prof_email` (`prof_email`),
  KEY `id_dip` (`id_dip`),
  KEY `idx_student_prof_status` (`status`),
  CONSTRAINT `prosklisi_se_trimeli_ibfk_1` FOREIGN KEY (`student_email`) REFERENCES `student` (`email_student`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prosklisi_se_trimeli_ibfk_2` FOREIGN KEY (`prof_email`) REFERENCES `professor` (`email_professor`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prosklisi_se_trimeli_ibfk_3` FOREIGN KEY (`id_dip`) REFERENCES `diplomatiki` (`id_diplomatiki`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prosklisi_se_trimeli`
--

LOCK TABLES `prosklisi_se_trimeli` WRITE;
/*!40000 ALTER TABLE `prosklisi_se_trimeli` DISABLE KEYS */;
/*!40000 ALTER TABLE `prosklisi_se_trimeli` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secretary`
--

DROP TABLE IF EXISTS `secretary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secretary` (
  `email_sec` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`email_sec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secretary`
--

LOCK TABLES `secretary` WRITE;
/*!40000 ALTER TABLE `secretary` DISABLE KEYS */;
/*!40000 ALTER TABLE `secretary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student` (
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `student_number` int NOT NULL,
  `street` varchar(255) NOT NULL,
  `number` varchar(10) NOT NULL,
  `city` varchar(255) NOT NULL,
  `postcode` varchar(5) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `landline_telephone` varchar(13) DEFAULT NULL,
  `mobile_telephone` varchar(13) NOT NULL,
  `email_student` varchar(255) NOT NULL,
  PRIMARY KEY (`email_student`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trimelis_epitropi_diplomatikis`
--

DROP TABLE IF EXISTS `trimelis_epitropi_diplomatikis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trimelis_epitropi_diplomatikis` (
  `id_dipl` int NOT NULL,
  `supervisor` varchar(255) NOT NULL,
  `member1` varchar(255) DEFAULT NULL,
  `member2` varchar(255) DEFAULT NULL,
  KEY `id_dipl` (`id_dipl`),
  KEY `supervisor` (`supervisor`),
  KEY `member1` (`member1`),
  KEY `member2` (`member2`),
  CONSTRAINT `trimelis_epitropi_diplomatikis_ibfk_1` FOREIGN KEY (`id_dipl`) REFERENCES `diplomatiki` (`id_diplomatiki`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `trimelis_epitropi_diplomatikis_ibfk_2` FOREIGN KEY (`supervisor`) REFERENCES `professor` (`email_professor`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `trimelis_epitropi_diplomatikis_ibfk_3` FOREIGN KEY (`member1`) REFERENCES `professor` (`email_professor`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `trimelis_epitropi_diplomatikis_ibfk_4` FOREIGN KEY (`member2`) REFERENCES `professor` (`email_professor`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trimelis_epitropi_diplomatikis`
--

LOCK TABLES `trimelis_epitropi_diplomatikis` WRITE;
/*!40000 ALTER TABLE `trimelis_epitropi_diplomatikis` DISABLE KEYS */;
/*!40000 ALTER TABLE `trimelis_epitropi_diplomatikis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'diplomatiki_support'
--
/*!50003 DROP PROCEDURE IF EXISTS `accept` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `accept`(IN pname VARCHAR(255), IN pcode VARCHAR(255), OUT error_code INT)
BEGIN 
    
    DECLARE prof1 VARCHAR(30);
	DECLARE prof2 VARCHAR(30);
    DECLARE y INT;
    DECLARE count INT;
    
    SET error_code = 0;
    SET y = 0;    
    
    SELECT COUNT(*)
    INTO count
    FROM trimelis_epitropi_diplomatikis
    WHERE id_dipl = pcode;
    
    SELECT member1, member2
    INTO prof1, prof2
    FROM trimelis_epitropi_diplomatikis
    WHERE id_dipl = pcode;
    
    SELECT prof1, prof2;
    
    IF (prof1 IS NULL) THEN
		UPDATE trimelis_epitropi_diplomatikis
		SET member1 = pname
		WHERE id_dipl = pcode;
        SET y = 1;
        
		UPDATE prosklisi_se_trimeli
		SET status = 'accepted', reply_date = CURDATE()
		WHERE prof_email = pname AND id_dip = pcode;
        
	END IF;
    
    IF (y = 0 AND prof2 IS NULL) THEN
    
    	UPDATE trimelis_epitropi_diplomatikis
		SET member2 = pname
		WHERE id_dipl = pcode;
        
        SET y = 2;
	
        UPDATE prosklisi_se_trimeli
		SET status = 'accepted', reply_date = CURDATE()
		WHERE prof_email = pname AND id_dip = pcode;
        
        
		UPDATE prosklisi_se_trimeli
        SET status = 'declined', reply_date = CURDATE()
        WHERE status = 'pending' AND id_dip = pcode;
        
        UPDATE anathesi_diplomatikis
        SET status = 'active' ,start_date = CURDATE()
        WHERE id_diploma = pcode AND status = 'pending';
            
	END IF;
    
	IF (count = 0) THEN
		-- Case 1: Diploma thesis does not exist (invalid pcode)
		SET error_code = 2; 

		UPDATE prosklisi_se_trimeli
		SET status = 'declined', reply_date = CURDATE()
		WHERE prof_email = pname AND id_dip = pcode;

	ELSEIF (y = 0) THEN
		-- Case 2: Neither member1 nor member2 was updated (no slot available)
		SELECT 'error: no available slot';
		SET error_code = 1; 
		
		UPDATE prosklisi_se_trimeli
		SET status = 'declined', reply_date = CURDATE()
		WHERE prof_email = pname AND id_dip = pcode;
	END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `assign_thesis` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `assign_thesis`(IN dip_id VARCHAR(255), IN am VARCHAR(255), IN stud_email VARCHAR(255), IN prof_email VARCHAR(255), OUT error_code INT)
BEGIN 
    DECLARE id_count INT;
    DECLARE stud_count INT;
    DECLARE stud_exist INT;
    
    SELECT COUNT(*)
    INTO id_count
    FROM diplomatiki
    WHERE email_prof = prof_email AND id_diplomatiki = CAST(dip_id AS SIGNED) AND status != 'given';
    
    SELECT COUNT(*)
    INTO stud_exist
    FROM student
    WHERE  email_student = stud_email AND student_number = am;
    
    SELECT COUNT(*)
    INTO stud_count
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND (status = 'pending' OR status = 'active' OR status = 'under examination' OR status = 'finished');
        
	IF (stud_count > 0) THEN
		SET error_code = 1;		# STUDENT ALREADY HAS A THESIS
	END IF;
    
    IF (stud_exist = 0) THEN
		SET error_code = 2;		# WRONG STUDENT INFO
	END IF;
    
	IF (id_count = 0) THEN
		SET error_code = 0;		# WRONG THESIS ID
	END IF;
    
	IF (stud_count = 0 AND id_count = 1 AND stud_exist = 1) THEN
		SET error_code = 3;		# CORRECT
        
        INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links) 
		VALUES(stud_email, dip_id, 'pending', NULL, NULL, NULL, NULL, NULL);
        
        INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1 , member2 )
        VALUES(dip_id, prof_email, NULL, NULL);
        
        UPDATE diplomatiki 
		SET status = 'given'
        WHERE id_diplomatiki = dip_id;
        
    END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `changeToFinished` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `changeToFinished`(IN diplomatiki_id INT, OUT status INT)
BEGIN
    DECLARE nemertes_status INT;
    DECLARE final_grade_status INT;
    DECLARE under_exam_status INT;

    SELECT COUNT(*) 
    INTO nemertes_status
    FROM anathesi_diplomatikis
    WHERE id_diploma = diplomatiki_id AND Nemertes_link IS NOT NULL;

    IF nemertes_status > 0 THEN 
        SET nemertes_status = 1;
    ELSE 
        SET nemertes_status = 0;
    END IF;

    SELECT COUNT(*) 
    INTO final_grade_status
    FROM eksetasi_diplomatikis
    WHERE id_diplomatikis = diplomatiki_id AND final_grade >= 5;

    IF final_grade_status > 0 THEN 
        SET final_grade_status = 1;
    ELSE 
        SET final_grade_status = 0;
    END IF;

    SELECT COUNT(*) 
    INTO under_exam_status
    FROM anathesi_diplomatikis
    WHERE id_diploma = diplomatiki_id AND anathesi_diplomatikis.status = 'under examination';

    IF under_exam_status > 0 THEN 
        SET under_exam_status = 1;
    ELSE 
        SET under_exam_status = 0;
    END IF;

    IF (nemertes_status + final_grade_status + under_exam_status = 3) THEN
        SET status = 1;
    ELSE
        SET status = 0;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `createNotes` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `createNotes`(IN professor_email VARCHAR(255), IN id_diploma INT, IN notes TEXT, OUT error_code INT)
BEGIN

DECLARE state ENUM('pending', 'active', 'canceled_by_student', 'canceled_by_professor', 'recalled', 'under examination', 'finished');
  
DECLARE supervisor_email VARCHAR(255);
DECLARE member1_email VARCHAR(255);
DECLARE member2_email VARCHAR(255);

DECLARE id_count INT;
DECLARE professor_count INT;

SELECT COUNT(*)
INTO id_count
FROM diplomatiki
WHERE id_diplomatiki = id_diploma;

SELECT COUNT(*)
INTO professor_count
FROM professor
WHERE email_professor = professor_email;


SET error_code=-1;

SELECT supervisor, member1, member2
INTO supervisor_email, member1_email, member2_email
FROM trimelis_epitropi_diplomatikis
WHERE id_dipl = id_diploma;


IF (id_count > 0 AND professor_count > 0) THEN 
	IF (professor_email = supervisor_email OR professor_email = member1_email OR professor_email = member2_email) THEN
		IF NOT EXISTS (SELECT * FROM professor_notes WHERE professor_notes.professor_email = professor_email AND professor_notes.id_diplom = id_diploma) THEN
			INSERT INTO professor_notes (professor_email, id_diplom, notes)
			VALUES (professor_email, id_diploma, notes);
            
            SET error_code=0;
		ELSE
			UPDATE professor_notes
			SET professor_notes.notes = notes
			WHERE professor_notes.professor_email = professor_email AND professor_notes.id_diplom = id_diploma;
		END IF;
	ELSE
		SET error_code = 1;      # You need to be a supervisor or member of this diploma to add notes.
	END IF;
END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `deleteById` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteById`(IN id INT)
BEGIN
    UPDATE diplomatiki SET status = 'available' WHERE id = id_diplomatiki;
    UPDATE anathesi_diplomatikis SET status = 'canceled_by_student' WHERE id = id_diploma AND status = "active";
    DELETE FROM professor_notes WHERE id = id_diplom;
    DELETE FROM trimelis_epitropi_diplomatikis WHERE id = id_dipl;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_date_diff` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_date_diff`(IN student_email VARCHAR(255))
BEGIN
    DECLARE startDate DATE;
    DECLARE currentDate DATE;
    DECLARE dateDiff INT;

    -- Set the current date
    SET currentDate = CURDATE();

    -- Fetch the start date for the given student email
    SELECT anathesi_diplomatikis.start_date INTO startDate
    FROM anathesi_diplomatikis
    WHERE anathesi_diplomatikis.email_stud = student_email AND (anathesi_diplomatikis.status = 'active' 
        OR anathesi_diplomatikis.status = 'under examination' 
        OR anathesi_diplomatikis.status = 'pending' 
        OR anathesi_diplomatikis.status = 'finished');

    -- Calculate the date difference
    SELECT DATEDIFF(currentDate, startDate) AS dateDiff;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `gradeSubmit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `gradeSubmit`(IN professor_email VARCHAR(255), IN id_diploma INT, IN grade DECIMAL(4, 2))
BEGIN

DECLARE supervisor VARCHAR(255);
DECLARE member1 VARCHAR(255);
DECLARE member2 VARCHAR(255);

SELECT trimelis_epitropi_diplomatikis.supervisor INTO supervisor FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

SELECT trimelis_epitropi_diplomatikis.member1 INTO member1 FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

SELECT trimelis_epitropi_diplomatikis.member2 INTO member2 FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;


IF (supervisor = professor_email) THEN
	UPDATE eksetasi_diplomatikis
	SET grade1 = grade
    WHERE eksetasi_diplomatikis.id_diplomatikis = id_diploma;
ELSEIF (member1 = professor_email) THEN
	UPDATE eksetasi_diplomatikis
	SET grade2 = grade
    WHERE eksetasi_diplomatikis.id_diplomatikis = id_diploma;
ELSEIF (member2 = professor_email) THEN
	UPDATE eksetasi_diplomatikis
	SET grade3 = grade
    WHERE eksetasi_diplomatikis.id_diplomatikis = id_diploma;
END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_to_dip` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_to_dip`(IN pr_email VARCHAR(100), IN dip_title VARCHAR(100), IN dip_descr VARCHAR(100), IN dip_stat VARCHAR(100), OUT dip_cod INT)
BEGIN 
	DECLARE title_count INT;
   
    
    SELECT COUNT(*)
    INTO title_count
    FROM diplomatiki
    WHERE title = dip_title;
    
    IF (title_count > 0) THEN
        SET dip_cod = 0;		# ALREADY EXISTS
        
	ELSE 
		INSERT INTO diplomatiki (email_prof, title, description, status) 
        VALUES (pr_email, dip_title, dip_descr, dip_stat);
        SET dip_cod = 1;		# SUCCESS
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `login` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `login`(IN pname VARCHAR(255), IN pcode VARCHAR(255), OUT ptype VARCHAR(255))
BEGIN 
    DECLARE student_email INT;
    DECLARE prof_email INT;
    DECLARE sec_email INT;
    
    
    SELECT COUNT(*)
    INTO student_email
    FROM student
    WHERE email_student = pname AND password = pcode;
    
    SELECT COUNT(*)
    INTO prof_email
    FROM professor
    WHERE email_professor = pname AND password = pcode;
    
    SELECT COUNT(*)
    INTO sec_email
    FROM secretary
    WHERE email_sec = pname AND password = pcode;

	IF (student_email > 0) THEN
		SET ptype = 'STUDENT';
		#SELECT 'STUDENT FOUND.';
	END IF;
    
	IF (prof_email > 0) THEN
		SET ptype = 'PROF';
	END IF;
    
	IF (sec_email > 0) THEN
		SET ptype = 'GRAM';
	END IF;
    
	IF (student_email = 0 AND prof_email = 0 AND sec_email = 0) THEN
		SET ptype = 'NONE';       #  WRONG COMBINATION OF USERNAME AND PASSWORD. PLEASE TRY AGAIN.
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `recall_thesis` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `recall_thesis`(
    IN dip_id INT, 
    IN stud_email VARCHAR(255), 
    IN prof_email VARCHAR(255), 
    IN meet_number VARCHAR(255),  -- Αριθμός της Γενικής Συνέλευσης
    IN meet_year VARCHAR(255),    -- Έτος της Γενικής Συνέλευσης
    OUT error_code INT
)
BEGIN 
    DECLARE id_count INT;
    DECLARE stud_count INT;
    DECLARE assign_date DATE;
    DECLARE assignment_status ENUM('pending', 'active', 'canceled_by_student', 'canceled_by_professor', 'recalled', 'under examination', 'finished');

    -- Προεπιλογή error_code = 0 (γενικό σφάλμα)
    SET error_code = 0;

    -- Έλεγχος αν η διπλωματική ανήκει στον καθηγητή και είναι 'given'
    SELECT COUNT(*)
    INTO id_count
    FROM diplomatiki
    WHERE email_prof = prof_email 
    AND id_diplomatiki = dip_id 
    AND status = 'given';
    
    SELECT COUNT(*)
    INTO stud_count
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND id_diploma = dip_id AND status = 'pending';

    -- Ανάκτηση κατάστασης και ημερομηνίας ανάθεσης
    SELECT status, start_date
    INTO assignment_status, assign_date
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND id_diploma = dip_id AND status = 'active';

    IF (stud_count = 1 AND id_count = 1) THEN
        -- Διαγραφή από σχετικoύς πίνακες
        DELETE FROM trimelis_epitropi_diplomatikis
        WHERE id_dipl = dip_id;  

        DELETE FROM prosklisi_se_trimeli
        WHERE id_dip = dip_id;

        -- Ενημέρωση κατάστασης διπλωματικής
        UPDATE diplomatiki
        SET status = 'available'
        WHERE id_diplomatiki = dip_id;

        -- Ενημέρωση κατάστασης ανάθεσης
        UPDATE anathesi_diplomatikis
        SET status = 'recalled'
        WHERE id_diploma = dip_id AND status != 'canceled_by_professor';

        SET error_code = 1; -- Επιτυχής ανάκληση
        
	END IF;
        
    IF (assignment_status = 'active' AND id_count = 1) THEN
    
		IF (meet_number < 0 OR meet_number > 10000 OR meet_number IS NULL OR meet_year < 2000 OR meet_year > 2030 OR meet_year IS NULL) THEN
			SET error_code = -4;
        -- Έλεγχος αν έχουν περάσει 2 έτη από την ανάθεση

		ELSEIF DATEDIFF(CURDATE(), assign_date) >= 730 THEN
			
				-- Ενημέρωση κατάστασης ανάθεσης σε 'canceled_by_professor'
				UPDATE anathesi_diplomatikis
				SET status = 'canceled_by_professor'
				WHERE id_diploma = dip_id AND status != 'recalled';
                
				UPDATE diplomatiki
                SET status = 'available'
                WHERE id_diplomatiki = dip_id;
				
				DELETE FROM trimelis_epitropi_diplomatikis
				WHERE id_dipl = dip_id;  
				
				INSERT INTO cancellations(id_d, meeting_number, meeting_year)
				VALUES (dip_id, meet_number, meet_year);

				SET error_code = 2; -- Επιτυχής ακύρωση ανάθεσης
			ELSE
        
            SET error_code = -2; -- Σφάλμα: Δεν έχουν παρέλθει 2 έτη
        END IF;
    END IF;
    
   IF id_count = 0 THEN
        -- Σφάλμα: Διπλωματική δεν ανήκει στον καθηγητή ή δεν είναι διαθέσιμη
        SET error_code = -1;
   END IF;
   
   IF (stud_count = 0 AND assignment_status IS NULL) THEN
		SET error_code = -3;
   END IF;
    
    
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `returnID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `returnID`(IN email VARCHAR(255), OUT id INT)
BEGIN
	SELECT id_diploma INTO id FROM anathesi_diplomatikis WHERE email_stud = email ORDER BY start_date DESC LIMIT 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sendRequest` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sendRequest`(IN s_email VARCHAR(255), IN p_email VARCHAR(255), OUT result_status VARCHAR(255))
BEGIN
	DECLARE rowCount INT;
    DECLARE id INT;
    
    SELECT COUNT(*) INTO rowCount FROM prosklisi_se_trimeli
    WHERE student_email = s_email AND prof_email = p_email AND (status = "pending" OR status = "accepted");
    
    SELECT id_diploma INTO id FROM anathesi_diplomatikis
    WHERE email_stud = s_email AND (anathesi_diplomatikis.status = 'active' 
        OR anathesi_diplomatikis.status = 'under examination' 
        OR anathesi_diplomatikis.status = 'pending' 
        OR anathesi_diplomatikis.status = 'finished');
    
    IF rowCount > 0 THEN
		SET result_status = 0;
	ELSE 
		INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
		VALUES (s_email, p_email, id, 'pending', NULL, CURDATE());
		SET result_status = 1;
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `setUnderExam` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `setUnderExam`(IN dip_id INT,  OUT error_code INT)
BEGIN 
DECLARE protocol INT;
DECLARE email_student VARCHAR(255);

SELECT anathesi_diplomatikis.protocol_number, email_stud INTO protocol, email_student FROM anathesi_diplomatikis 
WHERE id_diploma = dip_id AND status = 'active';

SELECT email_student;

IF (protocol IS NOT NULL) THEN
	UPDATE anathesi_diplomatikis 
	SET status = 'under examination' 
	WHERE id_diploma = dip_id AND status = 'active';
       
    INSERT INTO eksetasi_diplomatikis(id_diplomatikis, email_st)
    VALUES (dip_id, email_student);
    
    SET error_code = 0;
ELSE 
	SET error_code = 1;
END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `updateExam` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `updateExam`(IN s_email VARCHAR(255), IN e_date DATETIME, IN e_room VARCHAR(255))
BEGIN
	DECLARE rowCount INT;
    DECLARE d_id INT;
    
    SELECT id_diploma INTO d_id FROM anathesi_diplomatikis WHERE email_stud = s_email ORDER BY start_date DESC LIMIT 1;
    
    SELECT COUNT(*) INTO rowCount FROM eksetasi_diplomatikis WHERE email_st = s_email AND id_diplomatikis = d_id;
    
    IF rowCount = 0 THEN
		INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade) VALUES
		(d_id, s_email, e_date, e_room, NULL, NULL, NULL, NULL);
	ELSE
		UPDATE eksetasi_diplomatikis SET exam_date = e_date, exam_room = e_room WHERE email_st = s_email AND id_diplomatikis = d_id;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `update_dip` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_dip`(IN id_dip INT, IN new_title VARCHAR(100), IN new_descr text, OUT dip_cod INT)
BEGIN 

	DECLARE title_count INT;
   
    SELECT COUNT(*)
    INTO title_count
    FROM diplomatiki
    WHERE title = new_title AND id_diplomatiki != id_dip;
    
    IF (title_count > 0) THEN
        SET dip_cod = 0;		# ALREADY EXISTS
        
	ELSE 
		UPDATE diplomatiki 
        SET title = new_title, description = new_descr
        WHERE id_diplomatiki = id_dip;
        
        SET dip_cod = 1;		# SUCCESS
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `upload_pdf` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `upload_pdf`(IN d_id VARCHAR(255))
BEGIN 
	DECLARE link VARCHAR(50);
	
    SET link = CONCAT('../uploads/pdf_link_topic/',d_id,'.pdf');
    
    UPDATE diplomatiki
    SET pdf_link_topic = link
    WHERE id_diplomatiki = CAST(d_id AS SIGNED);

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-18 19:51:42
