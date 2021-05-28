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

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `persist_code`, `avatar`, `self_description`, `reset_password_code`, `permissions`, `is_activated`, `role_id`, `last_login`, `created_at`, `updated_at`, `deleted_at`, `is_superuser`, `is_subscribed`) VALUES (1,'Антон','Девятов ','differ@list.ru','$2y$10$sLCa7Md.UQurS5Rwbe6FQ.4Sv2YMWiwMYfwmJy9omZYChvbnNdVZm','$2y$10$jqTyZdubH2cn0n/Vl03RoOlVspJkOChS3cKeIR.dtVCOFLRFlDlpy','1612476603_image.jpg',NULL,NULL,NULL,1,1,'2021-05-28 14:02:12','2020-10-01 10:00:00','2021-05-28 14:02:12',NULL,1,0),(8,'Александр','Петров','petrov@mail.ru','$2y$10$IZRzIKhaqJcjI/Hlj2r0suhc77iBEQuTkfGKhs6MTWJJYeCzEGsbu','$2y$10$wkDMoKnk.AWB.Ht2yup9uu57Og7/TXGshGIB8Z65NY.9mq4FMGqKq','1621948977_Dm940oyXoAUHICs.jpg',NULL,NULL,NULL,0,2,'2021-05-25 16:39:02','2020-10-13 14:27:09','2021-05-25 16:53:32',NULL,0,0),(10,'Матвей','Иванов','ivanov@mail.ru','$2y$10$nJyT16ZJPaG3wvMjxnUjlukzZoQvohuPQBUPW1yIBaNaqm5KqzW3O','$2y$10$h6bOPIlIoyakGQ8W4r5K8Opp/sblT5Ux.87SeyI.Lk11XU0Xu2HU2','1612477398_20190829-IMG_7045_resize.jpg','Такая себе информация )',NULL,NULL,1,3,'2021-05-23 21:07:45','2021-02-01 15:50:58','2021-05-23 21:07:45',NULL,0,0),(11,'Альберт','Эйнштейн','albert@gmail.com','$2y$10$QgjcoisCvh8Ic7xAi2noUeoucY8cboG9b7OBQGckc3ISYzhdmNo8m','$2y$10$FedNOU7RG9/.5rcgMH2sG.hfwqL7b7LvDBDZPLT25D.M2RZBfb/my','1621948440_hello_html_m3eafe2cf.jpg',NULL,NULL,NULL,1,2,'2021-05-28 14:54:40','2021-05-23 17:04:34','2021-05-28 15:15:47',NULL,0,0);