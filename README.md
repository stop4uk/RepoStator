RMS.REPOStator (Система управления отчетами). Умеет создавать показатели, динамически их встраивать в формы передачи сведений и формировать статистику по ним как из шаблонов, так и по наборным показателям.
Умеет разграничивать права доступа к системе в целом и разделам


СОСТАВЛЯЮЩИЕ
-------------------
    * Отчеты              Основная единица учета
    * Константы           Показатели, необходимые к передаче и, входящие в состав отчета
    * Структуры           Группа или множество групп показателей привязанных к отчету (форма передачи)
    * Правила сложения    Простые математические правила расчета из данных показателей 
    * Шаблоны             Параметры построения итоговых сведений из показателей и мат. правил

УСТАНОВКА
-------------------
```ssh
git clone git@github.com:stop4uk/repostator YOUR_APP
cd YOUR_APP
cp .env.example .env
```
Отредактируйте получившийся .env файл под Ваши нужны
```ssh
make build && make start
make first-run
```

ИСПОЛЬЗОВАНИЕ
-------------------
    * make build
    * make start/stop/restart Запуск/Остановка и Рестарт всех контейнеров
    * make forst-run Автоматическое выполнение composer-install и yii/migrate
    * make ssh Переход в WEB контейнер (с приложением)
    * make exec/exec-bash YOUR_COMMAND Выполнение комманды YOUR_COMMAND внутри WEB контейнера
    * make composer-install/composer-install Обновить/Установить пакеты и зависимости composer'a 
    * make migrate Автоматически найти и применить новые миграции
RMS.REPOStator доступен по стандартному web порту (80). Логин#пароль admin@admin.loc # 12345

========================================
=

RMS.Reposator (Report Management System). It is able to create indicators, dynamically embed them in information transfer forms and generate statistics on them from both templates and composite indicators.
Knows how to differentiate access rights to the system as a whole and sections


THE COMPONENTS
-------------------
    * Reports are the main accounting unit
    * Constants of Indicators required for transmission and included in the report
    * Structures A group or set of groups of indicators linked to a report (transmission form)
    * Addition rules are simple mathematical rules for calculating data from indicators 
    * Templates for building summary information from indicators and mat. rules

INSTALLATION
-------------------
```ssh
git clone git@github.com:stop4uk/repostator YOUR_APP
cd YOUR_APP
cp .env.example .env
```
Edit the resulting .env file to suit your needs
```ssh
make build && make start
make first-run
```

USAGE
-------------------
    * make build
    * make start/stop/restart Startup/Stopping and Restarting all containers
    * make forst-run Automatic execution of composer-install and yii/migrate
    * make ssh Transfer to a WEB container (with an application)
    * make exec/exec-bash YOUR_COMMAND Executing YOUR_COMMAND command inside a WEB container
    * make composer-install/composer-install Update/Install composer packages and dependencies
    * make migrate Automatically finds and applies new
      
RMS migrations.Reposator is available on the standard web port (80). Login#password admin@admin.loc # 12345
