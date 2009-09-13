-- MySQL dump 10.13  Distrib 5.1.37, for unknown-linux-gnu (x86_64)
--
-- Host: localhost    Database: db019
-- ------------------------------------------------------
-- Server version	5.1.37

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
-- Table structure for table `parts`
--

DROP TABLE IF EXISTS `parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `description` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parts`
--

LOCK TABLES `parts` WRITE;
/*!40000 ALTER TABLE `parts` DISABLE KEYS */;
INSERT INTO `parts` VALUES (1,'about','Short descriptiondwsdsdыва'),(3,'coomon','вавыhhhыы');
/*!40000 ALTER TABLE `parts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  `theme_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `images` varchar(40) NOT NULL,
  `modifed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modifed_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,0,3,1,'fgdfg','2009-09-07 07:41:22','','0000-00-00 00:00:00',0),(2,1,1,1,'[quot]fgdfg[/quot]&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;','2009-09-10 08:48:48','','0000-00-00 00:00:00',0),(3,2,1,1,'[quot][quot]fgdfg[/quot]&Atilde;','2009-09-10 08:56:35','','0000-00-00 00:00:00',0),(4,3,3,1,'[quot][quot][quot]fgdfg[/quot]&Atilde;','2009-09-10 09:11:55','','0000-00-00 00:00:00',0),(5,2,3,1,'&ETH;&deg;&ETH;&deg;&ETH;&deg;','2009-09-10 10:29:52','','0000-00-00 00:00:00',0),(6,2,3,1,'&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&sup2;','2009-09-10 10:30:30','','0000-00-00 00:00:00',0),(7,6,3,1,'ввв','2009-09-10 10:33:34','','0000-00-00 00:00:00',0),(8,0,3,2,'И что, оно работает?','2009-09-11 07:32:54','','0000-00-00 00:00:00',0),(9,8,1,2,'[quot]И что, оно работает?[/quot]\r\nПринципиально - работает. Но не совсем понятно как именно','2009-09-11 07:42:52','','0000-00-00 00:00:00',0),(10,0,3,2,'А где личный кабинет?\r\n','2009-09-11 10:16:13','','0000-00-00 00:00:00',0),(11,10,1,2,'[quot]А где личный кабинет?\r\n[/quot]О! Точно! Сейчас забубеним!','2009-09-11 10:16:43','','0000-00-00 00:00:00',0),(13,11,1,2,'[quot][quot]А где личный кабинет?\r\n[/quot]О! Точно! Сейчас забубеним![/quot]\r\nСверху, клик по имени','2009-09-13 07:00:08','','0000-00-00 00:00:00',0),(14,0,1,2,'А теперь просто накидаем сообщений для кучи','2009-09-13 07:03:08','','0000-00-00 00:00:00',0),(15,14,1,2,'[quot]А теперь просто накидаем сообщений для кучи[/quot]Чтобы постраничный вывод проверить','2009-09-13 07:03:43','','0000-00-00 00:00:00',0),(16,15,3,2,'[quot][quot]А теперь просто накидаем сообщений для кучи[/quot]Чтобы постраничный вывод проверить[/quot]Постраничный вывод есть. Хочу теперь дерево!','2009-09-13 07:44:45','','0000-00-00 00:00:00',0),(17,0,1,3,'00','2009-09-13 12:58:50','','0000-00-00 00:00:00',0),(18,17,1,3,'01','2009-09-13 12:59:03','','0000-00-00 00:00:00',0),(19,17,1,3,'02','2009-09-13 12:59:15','','0000-00-00 00:00:00',0),(20,18,1,3,'11','2009-09-13 12:59:23','','0000-00-00 00:00:00',0),(21,20,1,3,'12','2009-09-13 14:28:59','','0000-00-00 00:00:00',0);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `themes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` int(10) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` varchar(120) NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,1,'Test Theme','Just first theme to test',1,'2009-09-08 12:35:48'),(2,1,'Вопросы по форуму','В этом разделе задавайте вопросы администрации.',0,'2009-09-11 07:08:18'),(3,1,'ttest','Тест дерева',0,'2009-09-13 12:57:12');
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `login` varchar(60) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `rights` int(2) unsigned NOT NULL DEFAULT '0',
  `cred` varchar(40) NOT NULL COMMENT 'User''s special check code',
  `conf_ppp` int(11) NOT NULL DEFAULT '20' COMMENT 'Post Per Page',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Serg','admin','90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad',1,'815855eb3ff73d8c2a4c932a590ad22ed60b8ecc',20),(3,'M3r@}{@k3P','megahacker','adcd7048512e64b48da55b027577886ee5a36350',0,'bbce6b39021a35d10d0bb2e8ede05aaa656cdd52',4),(4,'Тестер01','tester01','adcd7048512e64b48da55b027577886ee5a36350',0,'46b3f697a98c77a1dfe7074f04cd8be1d9077e73',20),(5,'Серёга','rakoth','adcd7048512e64b48da55b027577886ee5a36350',0,'699004ff6eebbb111538647d64622a428bcf1b5a',20),(30,'Имя','tester02','adcd7048512e64b48da55b027577886ee5a36350',0,'6d47e2786bc829e8bbb39e76d18c9e6fe66ab13b',20);
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

-- Dump completed on 2009-09-13 21:38:11
