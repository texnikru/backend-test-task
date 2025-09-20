create table if not exists products
(
    uuid varchar(255) not null comment 'UUID товара',
    category varchar(255) not null comment 'Категория товара',
    is_active tinyint default 1  not null comment 'Флаг активности',
    -- У товаров нет имён. Есть названия. Лучше обозвать колонку "title". Какие услуги? Ошибка в комменте?
    name text default '' not null comment 'Тип услуги',
    description text null comment 'Описание товара',
    thumbnail varchar(255) null comment 'Ссылка на картинку',
    price float not null comment 'Цена'
)
    comment 'Товары';

create index is_active_idx on products (is_active);
create unique index uuid_idx on products (uuid) using hash;
-- Денормализованные данные. Лучше использовать числовой ключ, но можно обыграть через индекс типа хэш
create index category_hash_idx on products (category) using hash;
