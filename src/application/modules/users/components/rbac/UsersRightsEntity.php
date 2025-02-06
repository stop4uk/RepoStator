<?php

namespace app\modules\users\components\rbac;

use yii\db\ActiveRecord;

/**
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 * @property int $created_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\components\rbac
 */
final class UsersRightsEntity extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%users_rights}}';
    }

    public function rules(): array
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'item_name' => 'Роль',
            'user_id' => 'Пользователь',
            'created_at' => 'Присвоена',
            'created_uid' => 'Присвоил',
        ];
    }
}
