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
            on delete cascade
);