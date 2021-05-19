create table if not exists users
(
    id                  int unsigned auto_increment
        primary key,
    first_name          varchar(191)         null,
    last_name           varchar(191)         null,
    email               varchar(191)         not null,
    password            varchar(191)         not null,
    activation_code     varchar(191)         null,
    persist_code        varchar(191)         null,
    avatar              varchar(255)         null,
    self_description    varchar(255)         null,
    reset_password_code varchar(191)         null,
    permissions         text                 null,
    is_activated        tinyint(1) default 0 not null,
    role_id             int unsigned         null,
    activated_at        timestamp            null,
    last_login          timestamp            null,
    created_at          timestamp            null,
    updated_at          timestamp            null,
    deleted_at          timestamp            null,
    is_superuser        tinyint(1) default 0 not null,
    constraint email_unique
        unique (email),
    constraint users_avatar_uindex
        unique (avatar)
)
    engine = innodb
    auto_increment = 1
    character set utf8
    collate utf8_unicode_ci;

create index act_code_index
    on users (activation_code);

create index admin_role_index
    on users (role_id);

create index reset_code_index
    on users (reset_password_code);