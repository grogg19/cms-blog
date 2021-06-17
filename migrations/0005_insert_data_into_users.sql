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

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `persist_code`, `avatar`, `self_description`, `reset_password_code`, `permissions`, `is_activated`, `role_id`, `last_login`, `created_at`, `updated_at`, `deleted_at`, `is_superuser`) VALUES (1,'Шелдон','Купер','admin@blog.ru','$2y$10$mYNspJy2WAmh7iM1YlPi0uS8FCH/ZOxYrpOue01KVvBH0rSGERuAa','$2y$10$8Wu0.lSP.gXUWle2dpF2IeiwotRyCcpsh.iNKCeuvpcs1ZrJukqXO','1623258268_avatar_sh_cooper.jpg','Физик-теоретик из Калтеха',NULL,NULL,1,1,'2021-06-16 23:51:38','2020-10-01 10:00:00','2021-06-16 23:51:38',NULL,1),(8,'Александр','Петров','petrov@mail.ru','$2y$10$IZRzIKhaqJcjI/Hlj2r0suhc77iBEQuTkfGKhs6MTWJJYeCzEGsbu','$2y$10$0TDvsv941.QvaobP/P9PuexoZtmHa8hThhz8cHt3bnqucZoKF13wm','1621948977_Dm940oyXoAUHICs.jpg',NULL,NULL,NULL,1,2,'2021-06-17 10:10:45','2020-10-13 14:27:09','2021-06-17 10:10:45',NULL,0),(10,'Томас','Андерсон','neo@mail.ru','$2y$10$rAr/uY422rzJwshD66qXX.DEieAh/QMjsfPgjj5Gjx2G8g7o94WDa','$2y$10$7063DYZAS8frseIPzeMeA.UMUgdsVEYGBQIkoxU30ncufiWorhuoa','1623772667_782.jpg','The chosen One',NULL,NULL,1,2,'2021-06-17 10:23:01','2021-02-01 15:50:58','2021-06-17 10:23:01',NULL,0),(11,'Альберт','Эйнштейн','albert@gmail.com','$2y$10$QgjcoisCvh8Ic7xAi2noUeoucY8cboG9b7OBQGckc3ISYzhdmNo8m','$2y$10$qZnbrQLyTXJlH7/CyD9p5e5NRB1/9t7fCU4mrnBRQDT/1o7X/Dsvy','1621948440_hello_html_m3eafe2cf.jpg',NULL,NULL,NULL,1,1,'2021-06-17 10:56:54','2021-05-23 17:04:34','2021-06-17 10:56:54',NULL,0),(13,'Руслан','Боширов','ruslan@mail.ru','$2y$10$3k3idKBe52eU3gPTNhvNQOtcoqNGsudFVGQXttLsFJah62C8.ruF2','$2y$10$LwhLjAJCjGAts3E897nQZuixbO/KgybeUOedeXcoPOzf0JenkfkKy','1623768062_0e70cecda270894ce11cc8001233eb74.jpg',NULL,NULL,NULL,1,3,'2021-06-17 10:56:09','2021-06-10 13:32:51','2021-06-17 10:56:09',NULL,0),(14,'Илон','Маск','elonmusk@gmail.com','$2y$10$r1jIYDKZUEwNjkiwgJUx4.cds0.2mleHeS1EdeuX5WqIdG8iwpd1a','$2y$10$BFS9yT5LzsVSBBqY5dpbBuhSi2JhZprPig4AyWbE30FswqtIkVvsu','1623414994_scale_1200.jpeg','Основатель SpaceX',NULL,NULL,1,3,'2021-06-17 10:43:38','2021-06-11 15:34:01','2021-06-17 10:43:38',NULL,0);