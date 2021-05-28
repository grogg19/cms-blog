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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
                         `id` int unsigned NOT NULL AUTO_INCREMENT,
                         `first_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
                         `last_name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
                         `email` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
                         `password` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
                         `persist_code` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
                         `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                         `self_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                         `reset_password_code` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
                         `permissions` text COLLATE utf8_unicode_ci,
                         `is_activated` tinyint(1) NOT NULL DEFAULT '0',
                         `role_id` int unsigned DEFAULT NULL,
                         `last_login` timestamp NULL DEFAULT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         `deleted_at` timestamp NULL DEFAULT NULL,
                         `is_superuser` tinyint(1) NOT NULL DEFAULT '0',
                         `is_subscribed` tinyint(1) DEFAULT '0',
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `email_unique` (`email`),
                         UNIQUE KEY `users_avatar_uindex` (`avatar`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;