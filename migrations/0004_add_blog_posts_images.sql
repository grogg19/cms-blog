create table if not exists blog_posts_images
(
    id        int unsigned auto_increment
        primary key,
    file_name varchar(255) not null,
    post_id   int unsigned not null,
    sort      int unsigned null,
    constraint blog_posts_images_blog_posts_id_fk
        foreign key (post_id) references blog_posts (id)
            on update cascade on delete cascade
)
    engine = innodb
    auto_increment = 1
    character set utf8
    collate utf8_unicode_ci;
