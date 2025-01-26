<?php

use yii\db\Migration;

final class m240412_144258_settingsDataInsert extends Migration
{
    const TABLE = '{{%settings}}';

    public function safeUp(): void
    {
        $this->batchInsert(self::TABLE, ["category", "key", "value", "description", "required", "sort"], [
            [
                'category' => 'auth',
                'key' => 'login_duration',
                'value' => '2592000',
                'description' => 'Время "жизни" сессии в секундах',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'login_sendEmailAfter',
                'value' => '1',
                'description' => 'Отправлять письмо-уведомление пользователю после успешной авторизации',
                'required' => 0,
                'sort' => 4,
            ],
            [
                'category' => 'auth',
                'key' => 'login_withoutVerification',
                'value' => '0',
                'description' => 'Авторизация без подтверждения Email адреса',
                'required' => 0,
                'sort' => 3,
            ],
            [
                'category' => 'auth',
                'key' => 'register_enableMain',
                'value' => '1',
                'description' => 'Разрешить самостоятельную регистрацию пользователей',
                'required' => 0,
                'sort' => 1,
            ],
            [
                'category' => 'report',
                'key' => 'make_dynamic',
                'value' => '1',
                'description' => 'Разрешить динамическое построение отчета (без предварительного шаблона)',
                'required' => 0,
                'sort' => null,
            ],
            [
                'category' => 'system',
                'key' => 'app_hostname',
                'value' => 'http://localhost',
                'description' => 'WEB адрес размещения системы. По умолчанию установлено значение http://localhost. Виду того, что все письма отправляются через очередь заданий, которая является консольной частью приложения, верный хост адрес указать необходимо. Если, его не указать, ссылки будут иметь вид http://localhost/ВАША_ССЫЛКА. HTTP:// или HTTPS:// также обязательно к указанию в адресе хоста',
                'required' => 1,
                'sort' => 7,
            ],
            [
                'category' => 'system',
                'key' => 'app_language',
                'value' => 'ru',
                'description' => 'Язык системы по умолчанию. Установлен в значение ru. Доступны варианты ru, by, kz, ua, en',
                'required' => 1,
                'sort' => 7,
            ],
            [
                'category' => 'system',
                'key' => 'app_language_date',
                'value' => 'php:d.m.Y',
                'description' => 'Формат даты. Указывается в полном (ICU) или PHP форматах. Например, dd.MM.yyyy или php:d.m.Y',
                'required' => 1,
                'sort' => null,
            ],
            [
                'category' => 'system',
                'key' => 'app_language_dateTime',
                'value' => 'php:d.m.Y H:i:s',
                'description' => 'Формат даты. Указывается в полном (ICU) или PHP форматах. Например, dd.MM.yyyy HH:mm:ss или php:d.m.Y H:i:s',
                'required' => 1,
                'sort' => null,
            ],
            [
                'category' => 'system',
                'key' => 'app_language_dateTimeMin',
                'value' => 'php:d.m.Y H:i',
                'description' => 'Формат даты. До минут. Указывается в полном (ICU) или PHP форматах. Например, dd.MM.yyyy HH:mm или php:d.m.Y H:i',
                'required' => 0,
                'sort' => null,
            ],
            [
                'category' => 'system',
                'key' => 'app_maintenance',
                'value' => '0',
                'description' => 'Режим обслуживания системы. Вход разрешен только пользователям с ролью "Администратор"',
                'required' => 0,
                'sort' => 6,
            ],
            [
                'category' => 'system',
                'key' => 'app_name',
                'value' => 'REPOStator',
                'description' => 'Название приложения в теге title шаблона',
                'required' => 0,
                'sort' => 7,
            ],
            [
                'category' => 'system',
                'key' => 'meta_description',
                'value' => '',
                'description' => 'Описание для SEO',
                'required' => 0,
                'sort' => 11,
            ],
            [
                'category' => 'system',
                'key' => 'meta_keywords',
                'value' => '',
                'description' => 'Ключевые слова для SEO',
                'required' => 0,
                'sort' => 10,
            ],
            [
                'category' => 'system',
                'key' => 'sender_email',
                'value' => 'no-reply@localhost',
                'description' => 'Адрес отправителя в Email письмах, отправляемых системой',
                'required' => 1,
                'sort' => 9,
            ],
            [
                'category' => 'system',
                'key' => 'sender_name',
                'value' => 'REPOStator. Reports & Statistics system',
                'description' => 'Имя отправителя в Email письмах, отправляемых системой',
                'required' => 1,
                'sort' => 8,
            ],
            [
                'category' => 'template',
                'key' => 'footer_enable',
                'value' => '1',
                'description' => 'Включить footer в шаблонах',
                'required' => 0,
                'sort' => 12,
            ],
            [
                'category' => 'template',
                'key' => 'footer_name',
                'value' => 'REPOStator',
                'description' => 'Название приложения в footer шаблона',
                'required' => 0,
                'sort' => 15,
            ],
            [
                'category' => 'template',
                'key' => 'footer_year',
                'value' => '1',
                'description' => 'Показывать текущий год в footer',
                'required' => 0,
                'sort' => 16,
            ],
            [
                'category' => 'auth',
                'key' => 'login_recovery',
                'value' => '1',
                'description' => 'Разрешить самостоятельное восстановление пароля',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'users_notification_add',
                'value' => '1',
                'description' => 'Отправлять уведомление о добавлении пользователя',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'users_notification_delete',
                'value' => '1',
                'description' => 'Отправлять уведомление об удалении пользователя',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'users_notification_change',
                'value' => '1',
                'description' => 'Отправлять уведомление об изменении пользователя',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'profile_enableChangeEmail',
                'value' => '1',
                'description' => 'Разрешить самостоятельную смену Email',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'report',
                'key' => 'notification_tComplete',
                'value' => '1',
                'description' => 'Отправлять уведомление о готовности отчета',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'report',
                'key' => 'notification_tError',
                'value' => '1',
                'description' => 'Отправлять уведомление об ошибке формирования',
                'required' => 0,
                'sort' => 5,
            ],
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}
