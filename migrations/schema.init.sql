create table if not exists products
(
    uuid          varchar(255) not null comment 'UUID товара',
    category_id   int          not null comment 'ID категории товара',
    category_name varchar(255) not null comment 'Категория товара',
    is_active     tinyint      not null comment 'Флаг активности',
    -- У товаров нет имён. Есть названия. Лучше обозвать колонку "title"
    name          text         not null comment 'Название товара',
    description   text null comment 'Описание товара',
    thumbnail     varchar(255) null comment 'Ссылка на картинку',
    price         float        not null comment 'Цена'
) comment 'Товары';

create unique index uuid_idx on products (uuid) using hash;
create index category_id_idx on products (category_id, is_active);

create table if not exists categories
(
    id   int primary key,
    name varchar(255) not null comment 'Название раздела',
) comment 'Категории товаров';
