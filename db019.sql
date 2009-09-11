-- MySQL dump 10.11
--
-- Host: localhost    Database: db019
-- ------------------------------------------------------
-- Server version	5.0.67-0ubuntu6

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
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `parts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `description` varchar(120) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

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
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL,
  `theme_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  `datetime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `images` varchar(40) NOT NULL,
  `modifed` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modifed_by` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,0,3,1,'fgdfg','2009-09-07 07:41:22','','0000-00-00 00:00:00',0),(2,1,1,1,'[quot]fgdfg[/quot]&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&deg;','2009-09-10 08:48:48','','0000-00-00 00:00:00',0),(3,2,1,1,'[quot][quot]fgdfg[/quot]&Atilde;','2009-09-10 08:56:35','','0000-00-00 00:00:00',0),(4,3,3,1,'[quot][quot][quot]fgdfg[/quot]&Atilde;','2009-09-10 09:11:55','','0000-00-00 00:00:00',0),(5,2,3,1,'&ETH;&deg;&ETH;&deg;&ETH;&deg;','2009-09-10 10:29:52','','0000-00-00 00:00:00',0),(6,2,3,1,'&ETH;&deg;&ETH;&deg;&ETH;&deg;&ETH;&sup2;','2009-09-10 10:30:30','','0000-00-00 00:00:00',0),(7,6,3,1,'ввв','2009-09-10 10:33:34','','0000-00-00 00:00:00',0),(8,0,3,2,'И что, оно работает?','2009-09-11 07:32:54','','0000-00-00 00:00:00',0),(9,8,1,2,'[quot]И что, оно работает?[/quot]\r\nПринципиально - работает. Но не совсем понятно как именно','2009-09-11 07:42:52','','0000-00-00 00:00:00',0);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `themes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `part_id` int(10) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` varchar(120) NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `themes`
--

LOCK TABLES `themes` WRITE;
/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
INSERT INTO `themes` VALUES (1,1,'Test Theme','Just first theme to test',1,'2009-09-08 12:35:48'),(2,1,'Вопросы по форуму','В этом разделе задавайте вопросы администрации.',0,'2009-09-11 07:08:18');
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `login` varchar(60) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `rights` int(2) unsigned NOT NULL default '0',
  `cred` varchar(40) NOT NULL COMMENT 'User''s special check code',
  `conf_ppp` int(11) NOT NULL default '20' COMMENT 'Post Per Page',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Serg','admin','90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad',1,'815855eb3ff73d8c2a4c932a590ad22ed60b8ecc',20),(3,'M3r@}{@k3P','megahacker','adcd7048512e64b48da55b027577886ee5a36350',0,'6143e7bf93d35ef2ffc4b63d3d21c10d4d597b41',20),(4,'Тестер01','tester01','adcd7048512e64b48da55b027577886ee5a36350',0,'46b3f697a98c77a1dfe7074f04cd8be1d9077e73',20),(5,'Серёга','rakoth','adcd7048512e64b48da55b027577886ee5a36350',0,'699004ff6eebbb111538647d64622a428bcf1b5a',20),(30,'Имя','tester02','adcd7048512e64b48da55b027577886ee5a36350',0,'6d47e2786bc829e8bbb39e76d18c9e6fe66ab13b',20);
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

-- Dump completed on 2009-09-11 10:09:57
