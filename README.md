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
git clone -b main git@github.com:stop4uk/repostator YOUR_APP
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
RMS.REPOStator доступен по стандартному web порту (80). Логин/пароль admin@admin.loc/12345