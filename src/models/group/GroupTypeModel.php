<?php

namespace app\models\group;

use Yii;
use yii\helpers\Json;

use app\base\BaseModel;
use app\entities\group\GroupTypeEntity;
use app\helpers\group\GroupTypeHelper;

/**
 * @property string $name;
 * @property strin|null $description
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\models\group
 */
class GroupTypeModel extends BaseModel
{
    public $name;
    public $description;

    public function init()
    {
        if ( $this->description ) {
            $this->description = Json::decode($this->description);
        }

        parent::init();
    }

    public function rules(): array
    {
        return [
            ['name', 'required', 'message' => Yii::t('models_error', 'Название обязательно')],
            ['name', 'string', 'length' => [4,64], 'message' => Yii::t('models_error', 'Длина от 4 до 64 символов')],
            [
                'name',
                'unique', 'targetClass' => GroupTypeEntity::class,
                'filter' =>  $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Название не уникально')
            ],
            ['description', 'string']
        ];
    }

    public function attributeLabels(): array
    {
        return GroupTypeHelper::labels();
    }
}