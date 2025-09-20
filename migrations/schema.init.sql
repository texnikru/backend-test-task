create table if not exists products
(
    uuid varchar(255) not null comment 'UUID товара',
    category varchar(255) not null comment 'Категория товара',
    is_active tinyint not null comment 'Флаг активности',
    -- У товаров нет имён. Есть названия. Лучше обозвать колонку "title"
    name text not null comment 'Название товара',
    description text null comment 'Описание товара',
    thumbnail varchar(255) null comment 'Ссылка на картинку',
    price float not null comment 'Цена'
)
    comment 'Товары';

-- Скорее всего индекс бесполезный т.к. будет низкоселективным. Обычно все товары "активны".
create index is_active_idx on products (is_active);

create unique index uuid_idx on products (uuid) using hash;

-- Денормализованные данные. Лучше использовать числовой ключ вида category_id, но можно обыграть через индекс типа хэш
create index category_hash_idx on products (category) using hash;
