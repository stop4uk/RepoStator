<?php

namespace stop4uk\users\models;

use app\components\base\BaseModel;
use app\helpers\HtmlPurifier;;
use stop4uk\users\{
    entities\UserEntity,
    helpers\UserHelper
};

/**
 * @property string $email
 * @property string $password
 * @property string|null $lastname
 * @property string $firstname
 * @property string|null $middlename
 * @property int|null $phone
 *
 * @author Stop4uk <stop4uk@yandex.ru>
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
            [['firstname', 'lastname'], 'required'],
            ['lastname', 'string', 'length' => [2, 48]],
            [['firstname', 'middlename'], 'string', 'length' => [2, 24]],
            [['lastname', 'firstname', 'middlename', 'phone'], 'filter', 'filter' => fn ($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return UserHelper::labels();
    }
}