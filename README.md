# Система тестирования пользователя, поддерживающая вопросы с нечеткой логикой

## Как развернуть локально

1. Выполнить `docker compose up` или `docker compose up -d` для запуска в режиме демона
2. Установить зависимости `docker compose run php composer install`
3. Применить миграции `docker compose run php php bin/console doctrine:migrations:migrate --no-interaction`
4. Приложение по умолчанию доступно по адресу http://localhost:8080/user-test
   - Порт можно задать через переменную окружения, например для запуска на порту `9999` нужно выполнить команду `APPLICATION_PORT=9999 docker compose up`

Доступные переменные окружения:

- `POSTGRES_VERSION`
- `POSTGRES_DB`
- `POSTGRES_USER`
- `POSTGRES_PASSWORD`

## Запустить тесты

`php bin/phpunit`

## Проверить код через phpstan

`vendor/bin/phpstan analyse`

## Полезные команды

- `docker compose exec postgres /bin/bash`
- `docker compose exec php /bin/bash`
