## Общие проблемы

1. Юнит-тесты? 
2. Добавить `composer.lock`, чтобы зафиксировать используемые версии пакетов
3. Мешанина из promoted-property и старых конструкторов и модификаторов readonly. Лучше использовать однообразную схему. 
3. Избыточная область видимости свойств и методов
4. Контракты методов/конструкторов не проставлены или слишком жидкие

## Датальнее

#### CartView

1. src/View/CartView.php#L36 Путаница с ценой строки и ценой корзины. 
2. src/View/CartView.php#L37 За товарами в репозиторий нужно ходить одним запросом, а не множеством.
3. src/View/CartView.php#L34 Ключ items можно инициализировать вместе с прочими ключами ответа.

#### JsonResponse

1. src/Controller/JsonResponse.php#L14 `Content-Type` всегда один, перенести в конструктор, чтобы не заставлять клиентов

#### ConnectorException

1. src/Infrastructure/ConnectorException.php#L7 Слишком общий предок. Может скрывать за собой даже `TypeError`. Лучше использовать `\Expection`.
2. src/Infrastructure/ConnectorException.php#L15 Кастомные реализации уйдут при наследовании от `\Exception`. Можно убрать.

#### CartManager (и его предок ConnectorFacade)

1. src/Repository/CartManager.php#L19 Вызывает `ConnectorFacade::build` в конструкторе, который устанавливает соединение когда мы даже не начали работу.
2. Пользует свойство `connector` от предка и не использует его умение делать reconnect. Просто расселить эту часть. 
3. src/Repository/CartManager.php#L22 Зависимость `LoggerInterface` оформлена через сеттер. Хорошо использовать общий подход через autowiring в конструктор.
3. src/Repository/CartManager.php#L35 Логируется только строка "Error" без детальной информации что за проблема.
4. src/Repository/CartManager.php#L33 Знает о `session_id()`, должен использовать менеджер сессий, который скажет какой у сессии id
5. src/Repository/CartManager.php#L42 Метод `getCart` говорит, что если корзины нет, то возвращать `null`, а не пустую корзину
6. src/Infrastructure/ConnectorFacade.php#L33 `ConnectorFacade.build` фактически делает ping в конструкторе, хотя это для поддержания уже установленного соединеня. На мороз.

#### Repository/ProductRepository (переименован в  DbalProductRepository)

1. src/Repository/ProductRepository.php#L22 Уязвим к SQL-инъекциям. Нужно использовать именованные параметры.
2. src/Repository/ProductRepository.php#L37 Метод `getByCategory` пытается воссоздать товар считывая из БД только одну колонку `id`
3. src/Repository/ProductRepository.php#L42 У нас "strict_types", а значит метод `make` будет фаталить т.к. считывает из БД в виде строк, а пытается манипулировать int, float и bool

#### Infrastructure/Connector (переименован в  RedisAwareKeyValueStorage)

1. src/Infrastructure/Connector.php#L28 Не логирует ошибки, что есть в требованиях задачи
2. src/Infrastructure/Connector.php#L28 Текст сообщения об ошибке не поясняет что случилось
3. В сервис проситься проверка доступности соединения. 

#### src/Controller/AddToCartController.php

1. src/Controller/AddToCartController.php#L25 Может отсутствовать ключ `productUuid`
2. src/Controller/AddToCartController.php#L30 Не факт, то товар был найден
3. src/Controller/AddToCartController.php#L22 Может отсутствовать ключ `quantity`. Можно купить отрицательное количество и магазин будет мне должен?
3. src/Controller/AddToCartController.php#L28 Отсутствует сохранение изменённой корзины 

#### src/Controller/GetCartController.php

1. src/Controller/GetCartController.php#L45 Сюда можем прийти только если операция была успешно выполнена и 404 код введёт клиента в заблуждение. Код 200. 
2. src/Controller/GetCartController.php#L36 Уже есть ранний выход, можно else вытащить из блока

#### src/Controller/GetProductsController.php

1. src/Controller/GetProductsController.php#L18 аргумент `$request` не используется
2. src/Controller/GetProductsController.php#L26 Значение может быть не задано, ожидается string

#### migrations/schema.init.sql

1. migrations/schema.init.sql#L4 Нет индекса по колонкам `uuid` и `category` хотя они используется для фильтрации. 
2. migrations/schema.init.sql#L3 Колонки `id` и `uuid` избыточны. `uuid` можно обозвать уникальным, а `id` больше нигде не используется.
3. migrations/schema.init.sql#L5 Колонка `category` выглядит как часть денормализации данных. Баг или фитча? Завести таблицу `category` и ссылаться на неё по `category_id`. Индекс на `category_id`.
4. migrations/schema.init.sql#L14 Скорее всего индекс по `is_active` бесполезный т.к. низкоселективный. Обычно все товары "активны".
5. migrations/schema.init.sql#L7 Коммент 'Тип услуги' -> 'Название услуги'
6. migrations/schema.init.sql#L7 Название колонки `name` не удачное, имхо. У товаров нет имён. Есть названия.