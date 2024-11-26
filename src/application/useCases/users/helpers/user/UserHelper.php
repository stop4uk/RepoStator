<?php

namespace app\useCases\users\helpers\user;

use Yii;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\Html;

use app\traits\GetLabelTrait;
use app\useCases\users\entities\user\UserEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\user
 */
final class UserHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'email' => Yii::t('entities', 'Email'),
            'password' => Yii::t('entities', 'Пароль'),
            'lastname' => Yii::t('entities', 'Фамилия'),
            'firstname' => Yii::t('entities', 'Имя'),
            'middlename' => Yii::t('entities', 'Отчество'),
            'phone' => Yii::t('entities', 'Телефон'),
            'account_status' => Yii::t('entities', 'Статус УЗ'),
            'account_key' => Yii::t('entities', 'Ключ УЗ'),
            'account_cpass_required' => Yii::t('entities', 'Требуется сменить пароль'),
            'groups' => Yii::t('models', 'Группы'),
            'name' => Yii::t('models', 'ФИО'),
            'hasGroup' => Yii::t('models', 'Группа'),
            'group' => Yii::t('models', 'Группа'),
            'rights' => Yii::t('models', 'Роли доступа'),
            'verifyPassword' => Yii::t('models', 'Подтверждение пароля')
        ];
    }

    public static function statuses(bool $withoutWait = false): array
    {
        $items = [
            UserEntity::STATUS_WAITCONFIRM => Yii::t('entities', 'Не подтвержден'),
            UserEntity::STATUS_ACTIVE => Yii::t('entities', 'Активен'),
            UserEntity::STATUS_BLOCKED => Yii::t('entities', 'Заблокирован'),
        ];

        if ( $withoutWait ) {
            unset($items[UserEntity::STATUS_WAITCONFIRM]);
        }

        return $items;
    }

    public static function statusesInColor(): array
    {
        return [
            UserEntity::STATUS_WAITCONFIRM => 'info',
            UserEntity::STATUS_ACTIVE => 'success',
            UserEntity::STATUS_BLOCKED => 'danger'
        ];
    }

    public static function statusName(int $statusCode): ?string
    {
        return ArrayHelper::getValue(static::statuses(), $statusCode);
    }

    public static function statusNameInColor(int $statusCode): ?string
    {
        return Html::tag('span', ArrayHelper::getValue(self::statuses(), $statusCode), [
            'class' => 'badge bg-' . ArrayHelper::getValue(self::statusesInColor(), $statusCode)
        ]);
    }

    public static function getShortName(array $fields): string
    {
        $lastname = $fields['lastname'];

        return implode(' ', [$lastname, self::getInitials($fields)]);
    }

    public static function getFullName(array $fields): string
    {
        return implode( ' ', $fields);
    }

    public static function generatePassword(string $password): string
    {
        return Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    private static function getInitials(array $fields): string
    {
        $initials = mb_strtoupper(mb_substr($fields['firstname'], 0, 1, 'UTF-8')) . '.';

        if ( $fields['middlename'] ) {
            $initials .= mb_strtoupper(mb_substr($fields['middlename'], 0, 1, 'UTF-8')) . '.';
        }

        return $initials;
    }
}