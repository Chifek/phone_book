create table users
(
  id       serial       not null
    constraint users_pkey
    primary key,
  email    varchar(40)  not null,
  password varchar(128) not null,
  login    varchar(40)
);

alter table users
  owner to postgres;

create table usersbook
(
  id         serial       not null
    constraint usersbook_pkey
    primary key,
  user_id    integer      not null,
  path       varchar(128) not null,
  first_name varchar(40)  not null,
  last_name  varchar(40),
  phone      varchar(13)  not null
);

alter table usersbook
  owner to postgres;

