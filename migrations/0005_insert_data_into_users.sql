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

INSERT INTO cms.users (id, first_name, last_name, email, password, activation_code, persist_code, avatar, self_description, reset_password_code, permissions, is_activated, role_id, activated_at, last_login, created_at, updated_at, deleted_at, is_superuser) VALUES (1, 'Антон', 'Девятов', 'differ@list.ru', '$2y$10$sLCa7Md.UQurS5Rwbe6FQ.4Sv2YMWiwMYfwmJy9omZYChvbnNdVZm', null, '$2y$10$8XsH2RucKKbPgSD1D2fW5.ogZjWCOO81cNVkS/Io59I47k9rJgiVS', '1612476603_image.jpg', null, null, null, 1, 1, '2021-02-08 11:46:07', '2021-05-24 09:24:07', '2020-10-01 10:00:00', '2021-05-24 09:24:07', null, 1);
INSERT INTO cms.users (id, first_name, last_name, email, password, activation_code, persist_code, avatar, self_description, reset_password_code, permissions, is_activated, role_id, activated_at, last_login, created_at, updated_at, deleted_at, is_superuser) VALUES (8, 'Александр', 'Петров', 'petrov@mail.ru', '$2y$10$IZRzIKhaqJcjI/Hlj2r0suhc77iBEQuTkfGKhs6MTWJJYeCzEGsbu', null, '$2y$10$wkDMoKnk.AWB.Ht2yup9uu57Og7/TXGshGIB8Z65NY.9mq4FMGqKq', '1621948977_Dm940oyXoAUHICs.jpg', null, null, null, 0, 2, '2021-02-08 11:46:11', '2021-05-25 16:39:02', '2020-10-13 14:27:09', '2021-05-25 16:53:32', null, 0);
INSERT INTO cms.users (id, first_name, last_name, email, password, activation_code, persist_code, avatar, self_description, reset_password_code, permissions, is_activated, role_id, activated_at, last_login, created_at, updated_at, deleted_at, is_superuser) VALUES (10, 'Матвей', 'Иванов', 'ivanov@mail.ru', '$2y$10$nJyT16ZJPaG3wvMjxnUjlukzZoQvohuPQBUPW1yIBaNaqm5KqzW3O', null, '$2y$10$h6bOPIlIoyakGQ8W4r5K8Opp/sblT5Ux.87SeyI.Lk11XU0Xu2HU2', '1612477398_20190829-IMG_7045_resize.jpg', 'Такая себе информация )', null, null, 1, 3, '2021-02-08 11:46:12', '2021-05-23 21:07:45', '2021-02-01 15:50:58', '2021-05-23 21:07:45', null, 0);
INSERT INTO cms.users (id, first_name, last_name, email, password, activation_code, persist_code, avatar, self_description, reset_password_code, permissions, is_activated, role_id, activated_at, last_login, created_at, updated_at, deleted_at, is_superuser) VALUES (11, 'Альберт', 'Эйнштейн', 'albert@gmail.com', '$2y$10$QgjcoisCvh8Ic7xAi2noUeoucY8cboG9b7OBQGckc3ISYzhdmNo8m', null, '$2y$10$JCoKwRgpt7nLHMUpzobQheLbLXzwCXOh.dRNZU3t/Ua42P1fp2Opa', '1621948440_hello_html_m3eafe2cf.jpg', null, null, null, 1, 3, null, '2021-05-25 16:03:56', '2021-05-23 17:04:34', '2021-05-25 16:14:00', null, 0);