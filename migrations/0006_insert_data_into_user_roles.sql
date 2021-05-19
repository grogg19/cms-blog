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

INSERT INTO cms.user_roles (id, name, code, description, permissions, is_system, created_at, updated_at) VALUES (1, 'Администратор', 'admin', 'Имеет полный доступ к админке', '1', 1, '2020-08-18 13:36:23', null);
INSERT INTO cms.user_roles (id, name, code, description, permissions, is_system, created_at, updated_at) VALUES (2, 'Контент-менеджер', 'content-manager', 'Может изменять/создавать статьи и модерирует комментарии к ним', '2', 0, '2020-10-16 09:23:42', null);
INSERT INTO cms.user_roles (id, name, code, description, permissions, is_system, created_at, updated_at) VALUES (3, 'Зарегистрированный пользователь', 'auth-user', 'Может оставлять комментарии', '3', 0, '2020-10-16 09:26:41', null);