create table if not exists user_roles
(
    id          int unsigned auto_increment
        primary key,
    name        varchar(191)         not null,
    code        varchar(191)         null,
    description text                 null,
    permissions text                 null,
    is_system   tinyint(1) default 0 not null,
    created_at  timestamp            null,
    updated_at  timestamp            null,
    constraint role_unique
        unique (name)
)
    engine = innodb
    auto_increment = 1
    character set utf8
    collate utf8_unicode_ci;

create index role_code_index
    on user_roles (code);