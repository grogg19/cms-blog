create table if not exists blog_posts
(
    id           int unsigned auto_increment
        primary key,
    user_id      int unsigned         null,
    title        varchar(200)         null,
    slug         varchar(200)         not null,
    excerpt      text                 null,
    content      longtext             null,
    published_at timestamp            null,
    published    tinyint(1) default 0 not null,
    created_at   timestamp            null,
    updated_at   timestamp            null
)
    engine = innodb
    auto_increment = 1
    character set utf8
    collate utf8_unicode_ci;

create index blog_posts_slug_index
    on blog_posts (slug);