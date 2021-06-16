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


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `persist_code`, `avatar`, `self_description`, `reset_password_code`, `permissions`, `is_activated`, `role_id`, `last_login`, `created_at`, `updated_at`, `deleted_at`, `is_superuser`) VALUES (1,'Шелдон','Купер','admin@blog.ru','$2y$10$mYNspJy2WAmh7iM1YlPi0uS8FCH/ZOxYrpOue01KVvBH0rSGERuAa','$2y$10$f0ijW/sPNk3thjTJyp/PIeWN7x1RTBvG.O8reQZ7MHuTewRX3/tSS','1623258268_avatar_sh_cooper.jpg','Физик-теоретик из Калтеха',NULL,NULL,1,1,'2021-06-15 23:20:31','2020-10-01 10:00:00','2021-06-15 23:20:31',NULL,1),(8,'Александр','Петров','petrov@mail.ru','$2y$10$IZRzIKhaqJcjI/Hlj2r0suhc77iBEQuTkfGKhs6MTWJJYeCzEGsbu','$2y$10$LF6VLaLEMnJ0faisgVWpwOwa5UgEibRq0x5gORNEtVO30RVx3waPO','1621948977_Dm940oyXoAUHICs.jpg',NULL,NULL,NULL,1,2,'2021-06-15 23:35:12','2020-10-13 14:27:09','2021-06-15 23:35:12',NULL,0),(10,'Томас','Андерсон','neo@mail.ru','$2y$10$rAr/uY422rzJwshD66qXX.DEieAh/QMjsfPgjj5Gjx2G8g7o94WDa','$2y$10$yA6MFyvCxAgvY1hXBPVshuXwd/bZC.k5qIRWbJZdZDjuLw0792UVi','1623772667_782.jpg','The chosen One',NULL,NULL,1,2,'2021-06-15 19:42:38','2021-02-01 15:50:58','2021-06-16 14:15:08',NULL,0),(11,'Альберт','Эйнштейн','albert@gmail.com','$2y$10$QgjcoisCvh8Ic7xAi2noUeoucY8cboG9b7OBQGckc3ISYzhdmNo8m','$2y$10$nJFODQ6k9OS4LljADS1kLOJEaIyvxjFhsdxbavw.11G3E7Ww3klwi','1621948440_hello_html_m3eafe2cf.jpg',NULL,NULL,NULL,1,1,'2021-06-16 13:56:59','2021-05-23 17:04:34','2021-06-16 13:56:59',NULL,0),(13,'Руслан','Боширов','ruslan@mail.ru','$2y$10$3k3idKBe52eU3gPTNhvNQOtcoqNGsudFVGQXttLsFJah62C8.ruF2','$2y$10$/EL40kxtWzbpXMpseI63VOmB7xoZV.t502faenyRSQVvP/inM1KpG','1623768062_0e70cecda270894ce11cc8001233eb74.jpg',NULL,NULL,NULL,1,3,'2021-06-16 00:15:56','2021-06-10 13:32:51','2021-06-16 00:15:56',NULL,0),(14,'Илон','Маск','elonmusk@gmail.com','$2y$10$8MVqmGUWkRI9IqPKMhm5/uEGpuaeERjvHpktYS8qYYvk39FXQ2JPS','$2y$10$Di3jhz6i7mZtaGt9eTbG1.3bNcjKKGM1EI1E3A496j7V62oIjhrzq','1623414994_scale_1200.jpeg','Основатель SpaceX',NULL,NULL,1,3,NULL,'2021-06-11 15:34:01','2021-06-16 14:21:12',NULL,0);