<?php

namespace app\modules\users\forms;

use Yii;
use yii\base\Model;

use app\modules\users\helpers\UserHelper;

/**
 * @property string $password
 * @property string $verifyPassword
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\forms
 */
final class UserPasswordChangeForm extends Model
{
    public $password;
    public $verifyPassword;

    public function rules(): array
    {
        return [
            [['password', 'verifyPassword'], 'required', 'message' => Yii::t('models_error', 'Все поля обязательны для заполнения')],
            [['password', 'verifyPassword'], 'string', 'min' => 5, 'message' => Yii::t('models_error', 'Минимальная длина пароля 5 символов')],
            ['password', 'compare', 'compareAttribute' => 'verifyPassword', 'message' => Yii::t('models_error', 'Пароль и подтверждение пароля не совпадают')],
        ];
    }

    public function attributeLabels(): array
    {
        return UserHelper::labels();
    }
}