-- MySQL dump 10.13  Distrib 9.6.0, for macos26.2 (arm64)
--
-- Host: localhost    Database: online_exam_db
-- ------------------------------------------------------
-- Server version	9.6.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `exam_attempts`
--

DROP TABLE IF EXISTS `exam_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exam_attempts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `exam_id` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `score` decimal(5,2) DEFAULT '0.00',
  `status` enum('in_progress','submitted','evaluated') DEFAULT 'in_progress',
  `tab_switches` int DEFAULT '0',
  `is_cheated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `exam_id` (`exam_id`),
  CONSTRAINT `exam_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_attempts_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_attempts`
--

LOCK TABLES `exam_attempts` WRITE;
/*!40000 ALTER TABLE `exam_attempts` DISABLE KEYS */;
INSERT INTO `exam_attempts` VALUES (1,2,1,'2026-05-19 02:36:19','2026-05-19 03:26:56',0.00,'evaluated',0,0),(2,4,1,'2026-05-19 16:45:09','2026-05-19 16:53:28',0.75,'evaluated',3,0),(3,5,1,'2026-09-08 16:19:14','2026-09-08 16:19:40',1.50,'evaluated',0,0),(4,5,3,'2026-09-08 16:19:56','2026-09-08 16:27:28',3.00,'evaluated',3,0),(5,5,1,'2026-09-08 16:31:36',NULL,0.00,'in_progress',1,0),(6,5,3,'2026-09-08 16:32:00','2026-09-08 16:33:25',5.00,'evaluated',1,0);
/*!40000 ALTER TABLE `exam_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text,
  `duration_minutes` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `passing_marks` decimal(5,2) NOT NULL,
  `total_marks` decimal(5,2) NOT NULL,
  `negative_marking_ratio` decimal(3,2) DEFAULT '0.00',
  `status` enum('draft','published','completed') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

LOCK TABLES `exams` WRITE;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
INSERT INTO `exams` VALUES (1,'Web Development 101','This is a basic exam on web development concepts including HTML, CSS, and JS.',120,'2026-05-20 10:00:00','2026-05-20 12:00:00',40.00,100.00,0.25,'published','2026-05-18 20:39:07',1),(3,'Scholarship Test - Global public school','Global Public School is a golden opportunity for students to showcase their academic talent and win exciting scholarship benefits. The test is designed to encourage young minds, identify potential, and support students in achieving quality education with confidence and excellence.',30,'2026-05-19 17:30:00','2026-05-19 17:59:00',10.00,20.00,0.00,'published','2026-05-19 11:27:16',1);
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` VALUES (5,2,'k',1),(6,2,'jk',0),(7,2,'k',0),(8,2,'jk',0),(9,3,'mn',0),(10,3,'nm',1),(11,3,'mn',0),(12,3,'nb',0),(17,4,'asdf',1),(18,4,'adf',0),(19,5,'sdfg',1),(20,5,'asdf',0),(21,6,'sfg',1),(22,6,'sfgsfdg',0),(23,7,'sdfg',1),(24,7,'sfg',0),(27,8,'adf',0),(28,8,'asdf',1),(29,8,'jkk',0),(30,9,'New Delhi',1),(31,9,'None of the above',0),(32,10,'7',1),(33,10,'None of the above',0),(34,11,'Rabindranath Tagore',1),(35,11,'None of the above',0),(36,12,'Cold',1),(37,12,'None of the above',0),(38,13,'Mars',1),(39,13,'None of the above',0),(40,14,'Children',1),(41,14,'None of the above',0),(42,15,'12',1),(43,15,'None of the above',0),(44,16,'Tiger',1),(45,16,'None of the above',0),(46,17,'Saturday',1),(47,17,'None of the above',0),(48,18,'Pink',1),(49,18,'None of the above',0),(50,19,'Pacific Ocean',1),(51,19,'None of the above',0),(52,20,'Mahatma Gandhi',1),(53,20,'None of the above',0),(54,21,'Joyful',1),(55,21,'None of the above',0),(56,22,'Peacock',1),(57,22,'None of the above',0),(58,23,'24',1),(59,23,'None of the above',0),(60,24,'Small',1),(61,24,'None of the above',0),(62,25,'Diwali',1),(63,25,'None of the above',0),(64,26,'Lotus',1),(65,26,'None of the above',0),(66,27,'Oxygen',1),(67,27,'None of the above',0),(68,28,'January',1),(69,28,'None of the above',0);
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exam_id` int NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('mcq','subjective') DEFAULT 'mcq',
  `marks` decimal(5,2) DEFAULT '1.00',
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (2,1,'bn','mcq',1.00,NULL),(3,1,'bhmnm','mcq',1.00,NULL),(4,1,'asdf','mcq',1.00,NULL),(5,1,'adfasdf','mcq',1.00,NULL),(6,1,'sfgsdfgsfgs','mcq',1.00,NULL),(7,1,'sdfgsdfghdfghdfghdfgh','mcq',1.00,NULL),(8,1,'asdf','mcq',1.00,NULL),(9,3,'What is the capital of India?','mcq',1.00,NULL),(10,3,'How many days are there in a week?','mcq',1.00,NULL),(11,3,'Who wrote the National Anthem of India?','mcq',1.00,NULL),(12,3,'What is the opposite of \'Hot\'?','mcq',1.00,NULL),(13,3,'Which planet is known as the Red Planet?','mcq',1.00,NULL),(14,3,'What is the plural of \'Child\'?','mcq',1.00,NULL),(15,3,'How many months are there in a year?','mcq',1.00,NULL),(16,3,'What is the national animal of India?','mcq',1.00,NULL),(17,3,'What comes after Friday?','mcq',1.00,NULL),(18,3,'Which color is made by mixing red and white?','mcq',1.00,NULL),(19,3,'What is the largest ocean in the world?','mcq',1.00,NULL),(20,3,'Who is known as the Father of the Nation in India?','mcq',1.00,NULL),(21,3,'What is the synonym of \'Happy\'?','mcq',1.00,NULL),(22,3,'Which bird is known for its beautiful feathers?','mcq',1.00,NULL),(23,3,'How many hours are there in a day?','mcq',1.00,NULL),(24,3,'What is the opposite of \'Big\'?','mcq',1.00,NULL),(25,3,'Which festival is known as the Festival of Lights?','mcq',1.00,NULL),(26,3,'What is the national flower of India?','mcq',1.00,NULL),(27,3,'Which gas do humans need to breathe?','mcq',1.00,NULL),(28,3,'What is the first month of the year?','mcq',1.00,NULL);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_answers`
--

DROP TABLE IF EXISTS `student_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attempt_id` int NOT NULL,
  `question_id` int NOT NULL,
  `selected_option_id` int DEFAULT NULL,
  `subjective_answer` text,
  `marks_obtained` decimal(5,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `attempt_id` (`attempt_id`),
  KEY `question_id` (`question_id`),
  KEY `selected_option_id` (`selected_option_id`),
  CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `exam_attempts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_answers_ibfk_3` FOREIGN KEY (`selected_option_id`) REFERENCES `options` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_answers`
--

LOCK TABLES `student_answers` WRITE;
/*!40000 ALTER TABLE `student_answers` DISABLE KEYS */;
INSERT INTO `student_answers` VALUES (2,2,2,5,NULL,1.00),(3,2,4,18,NULL,-0.25),(4,3,2,5,NULL,1.00),(5,3,3,11,NULL,-0.25),(6,3,4,18,NULL,-0.25),(7,3,5,19,NULL,1.00),(8,4,17,46,NULL,1.00),(9,4,19,51,NULL,0.00),(10,4,21,54,NULL,1.00),(11,4,22,56,NULL,1.00),(12,6,9,30,NULL,1.00),(13,6,10,32,NULL,1.00),(14,6,11,35,NULL,0.00),(15,6,12,36,NULL,1.00),(16,6,13,38,NULL,1.00),(17,6,15,43,NULL,0.00),(18,6,16,44,NULL,1.00);
/*!40000 ALTER TABLE `student_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','examiner') DEFAULT 'student',
  `city` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','admin@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',NULL,'active','2026-05-18 20:26:17'),(2,'John Doe','student@example.com','$2y$12$lXb/0cZqNjl.pU9UV7QueumJ0Ou3g55wkCGl.gp231uYDRP5/3mxe','student','New York','active','2026-05-18 20:56:51'),(3,'Final Test','admin@test.com','$2y$12$DXVB0XpdAfw.797JmfN4P.24h6RK/5uWdiDUP3f3TM.tomypwxFlK','student','New York','active','2026-05-18 23:06:18'),(4,'Kush','kush@gmail.com','$2y$12$c5R9LVmkmw4aaipnMzEdge6PzVEwhlmfAM6rYXiPhcVHybb/u7fnK','student','Delhi','active','2026-05-19 11:14:36'),(5,'Lavkush kumar','kushkrg@gmail.com','$2y$12$SKJ8on5Z/btlSQxjXz7UIOgOTbW6Mj3NFMAp.hLqrwubZGsP7IN6u','student','Nasriganj','active','2026-09-08 10:48:52');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-08 16:46:34
