create table if not exists blog_post_comments
(
    id int auto_increment,
    post_id int not null,
    user_id int not null,
    content text not null,
    has_moderated bool default 0 null,
    created_at timestamp null,
    updated_at timestamp null,
    constraint blog_post_comments_pk
    primary key (id)
);