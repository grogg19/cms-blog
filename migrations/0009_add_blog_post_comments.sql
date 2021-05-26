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


DROP TABLE IF EXISTS blog_post_comments;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
--
-- Table structure for table `blog_post_comments`
--
create table blog_post_comments
(
    id int unsigned auto_increment
        primary key,
    post_id int unsigned not null,
    user_id int unsigned not null,
    content text not null,
    has_moderated tinyint(1) default 0 null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint blog_post_comments_blog_posts_id_fk
        foreign key (post_id) references blog_posts (id)
            on delete cascade,
    constraint blog_post_comments_blog_users_id_fk
    foreign key (user_id) references users (id)
    on delete cascade
);
/*!40101 SET character_set_client = @saved_cs_client */;