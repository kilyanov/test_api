Клонирование
```bash

git clone git@github.com:kilyanov/test_api.git
```
Настройка **Docker**

Предполагается что в системе уже установлен docker и docker compose.

Копируем файл настроек:

```
cp .env.dist .env
```
После внесения изменения в файл настроек его нужно скопировать в корень проекта для использования самим приложением,
так же если есть необходимость своих локальных настроек - можно в корень проекта создать файл .env.loc, который будет
использоваться без учета параметров файла .env

IP адреса Redis и MySQL смотреть в докере, они присваиваются автоматически, после запуска контейнера

Сборка проекта

```bash
docker compose build
```

Запуск контейнера

```bash

docker compose up -d  

```
Установка зависимостей (предполагается что Composer у вас стоит глобальный, если нет то надо скопировать файл composer.phar в корень проекта)

```bash

docker compose exec php composer install
```
Обновить проект
```bash

docker compose exec php composer update
```

Применение миграций
```bash

docker compose exec php ./yii migrate
```

Создание роли Admin
```bash

docker compose exec php ./yii role
```

Создание пользователя логин и пароль admin/admin
```bash

docker compose exec php ./yii user
```

Права на каталоги:
используя консоль "переходим" в каталог с проектом и проставляем права

```bash

chmod 777 -R web
chmod 777 -R runtime
chmod 777 -R web/assets
```

После запуска Docker (сеть сделал default) и внесения необходимых настроек в .env
Swagger будет доступен по адресу http://127.0.0.1:8000/swagger/v1/default/doc

Авторизация реализована через bearer или access-token - как кому удобнее

GRUD по адресу http://127.0.0.1:8000/ - по оформлению сильно не заморачивался