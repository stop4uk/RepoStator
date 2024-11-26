<?php

namespace forms\user;

use app\components\base\BaseAR;
use app\entities\user\{entities\user\UserEmailchangeEntity, entities\user\UserEntity};
use app\helpers\{HtmlPurifier};
use helpers\user\UserHelper;
use Yii;
use yii\base\Model;

/**
 * @property string $email
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\forms\user
 */
final class UserEmailChangeForm extends Model
{
    public $email;

    public function rules(): array
    {
        return [
            ['email', 'required', 'message' => Yii::t('models_error', 'Укажите новый Email')],
            ['email', 'email', 'message' => Yii::t('models_error', 'Email адрес должен соответствовать общепринятым стандартам')],
            ['email', 'string', 'length' => [6, 58], 'message' => Yii::t('models_error', 'Длина 6 до 58 символов')],
            [
                'email',
                'unique', 'targetClass' => \entities\user\UserEntity::class,
                'filter' => function($query) {
                    $query->andFilterWhere(['record_status' => BaseAR::RSTATUS_ACTIVE])
                        ->andFilterWhere(['!=', 'id', Yii::$app->getUser()->id]);
                },
                'message' => Yii::t('models_error', 'Данный Email адрес уже зарегистрирован в системе')
            ],
            [
                'email',
                'unique', 'targetClass' => \entities\user\UserEmailchangeEntity::class,
                'filter' => ['record_status' => BaseAR::RSTATUS_ACTIVE],
                'message' => Yii::t('models_error', 'Заявка с этим Email уже подана')
            ],
            ['email', 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return UserHelper::labels();
    }
}