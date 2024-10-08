Система построения отчетов, сбора цифровых показателей и формирования статистики.
- Компонент авторизации и регистрации
- Компонент распределения прав доступа
- Компонент формирования групп с подчинением
- Неограниченное число составляющих
- Формирование статистики из динамического набора части составляющих
- Формирование статистики на основнии загруженного файла xlsx#ods
- Базируется на Yii2 Basic

СОСТАВЛЯЮЩИЕ
-------------------
    Отчеты              Основная единица учета
    Константы           Показатели, необходимые к передаче и, входящие в состав отчета
    Структуры           Группа или множество групп показателей привязанных к отчету
    Правила сложения    Простые математические правила расчета из данных показателей 
    Шаблоны             Параметры построения итоговых сведений из показателей и мат. правил

СТРУКТУРА ДИРЕКТОРИЙ
-------------------
    config/             Конфигурационные файлы приложения
    data/               Upload файлы (шаблоны отчетов)
    environments/       Файлы окружений для инициализации приложения
    public/             Входной скрипт и ассеты
    resources/          Открытые и изменяемые ресурсы (css # js # email # миграции и т.д.)
    src/                Исходный код приложения (контроллеры # модели и т.д.)
    storage/            Логи # кеш # временные файлы и т.д.

ТРЕБОВАНИЯ
------------
Миниально необходимые требования: 
- PHP 8
- MySQL
- Composer
- Apache/Nginx


УСТАНОВКА
------------
Качаете архив, размещаете в нужной папке, правите файлы в environments/{dev|prod}/config под Ваши нужды
- Меняете адрес подключения до БД
- Указываете настройки для отправки почты и т.д.
- Папки public/assets и storage должны иметь максимальные разрешения (777)
- Затем выполняете инициализацию приложения dev/prod и применяете миграции. Все действия производятся в корневой директории системы (где лежит файл composer.json)
```php
composer install
php init
php yii migrate
```


ВАЖНО!
------------
- Учетная запись по умолчанию одна: admin@admin.loc с паролем 12345
- В работе системы применяются очереди. В обязательном порядке для отправки уведомлений 
посредством Email и, в случае необходимости, для формирования отчетов (указывается при 
создании шаблона формирования). Для выполнения заданий, необходим воркер.
Как его делать, можно почитать тут: [https://www.yiiframework.com/extension/yiisoft/yii2-queue/doc/guide/2.0/en/worker](https://www.yiiframework.com/extension/yiisoft/yii2-queue/doc/guide/2.0/en/worker)
Или тут (для Windows): Как его делать, можно почитать тут: [https://superuser.com/questions/985734/how-do-i-run-a-windows-command-as-a-service](https://superuser.com/questions/985734/how-do-i-run-a-windows-command-as-a-service)
Все ожидающие задачи, которые стоят в очереди на исполнение может поглядеть администратор


ДОПОЛНИТЕЛЬНЫЕ НАСТРОЙКИ
------------
Для работы желательно применять очистку файлов сформированных отчетов.
Чтобы файлы чистились, Вам необходимо добавить задание в cron/планировщик
Команда для задания
```php
php yii file/clean
```
