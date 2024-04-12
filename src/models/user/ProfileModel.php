<?php

namespace app\models\user;

use Yii;
use yii\helpers\HtmlPurifier;

use app\base\BaseModel;
use app\helpers\user\UserHelper;
use app\entities\user\UserEntity;

/**
 * @property string $email
 * @property string $password
 * @property string|null $lastname
 * @property string $firstname
 * @property string|null $middlename
 * @property int $phone
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\models\user
 */
final class ProfileModel extends BaseModel
{
    public $lastname;
    public $firstname;
    public $middlename;
    public $phone;

    public function __construct(UserEntity $entity, $config = [])
    {
        parent::__construct($entity, $config);
    }

    public function rules(): array
    {
        return [
            [['firstname', 'lastname', 'phone'], 'required'],
            ['lastname', 'string', 'length' => [2, 48]],
            [['firstname', 'middlename'], 'string', 'length' => [2, 24]],
            ['phone', 'string', 'length' => [10, 10]],
            [
                'phone',
                'unique', 'targetClass' => UserEntity::class,
                'filter' => $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Данный телефон уже зарегистрирован в системе')
            ],
            [['lastname', 'firstname', 'middlename', 'phone'], 'filter', 'filter' => fn ($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return UserHelper::labels();
    }
}