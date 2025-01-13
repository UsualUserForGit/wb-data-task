
>Нужно стянуть все данные по описанным эндпоинтам и сохранить в БД.

###### Стек:

- Laravel 11.8
- MySQL 8.0.39
- PHP 8.2 и выше

###### Установка проекта

1. Распаковывать архив в любую папку


2. Установить зависимости

```bash
composer install
```

3. Для того, чтобы настроить файл окружения (.env), нужно переименовать файл .env.example в .env. Все настройки базы данных там написаны.

5. Генерация ключа приложения

```bash
php artisan key:generate
```

6. Выполняем миграцию для создания таблиц в базе данных

```bash
php artisan migrate
```

7. Для переноса данных запустите команду

```bash
php artisan fetch:api-data {название сущности} --dateFrom={дата с} --dateTo={дата по} --key={API-ключ} --limit={лимит}
```

- `{название сущности}` - название сущностей во множественном числе: `stocks`,
  `incomes`, `sales`, `orders`.
- `{дата с}` - дата начала выгрузки (в формате `YYYY-MM-DD`)
- `{дата по}` - дата окончания выгрузки (в формате `YYYY-MM-DD`)``
- `{API-ключ}` - ваш API-ключ
- `{лимит}` - лимит для выгрузки записи

Пример успешного запроса:

```bash
php artisan fetch:api-data orders  --dateFrom=2024-05-01 --dateTo=2024-05-28 --key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie --limit=50
```

Ответ

```bash
Orders data fetched successfully
```

###### Доступ к БД через PhpMyAdmin или MySQL:

[PhpMyAdmin](https://www.phpmyadmin.co/)

Сервер : ngzzx.h.filess.io:3307
Пользователь : wbdata_swimnameor
Пароль : root
БД : wbdata_swimnameor

###### Названия таблиц

- `stocks`
- `incomes`
- `sales`
- `orders`
