<?php

namespace app\modules\users\models;

use Yii;
use yii\helpers\Json;

use app\components\base\BaseModel;
use app\modules\users\{
    entities\GroupEntity,
    repositories\GroupTypeRepository,
    helpers\GroupHelper
};

/**
 * @property string|null $code
 * @property string $name
 * @property string|null $name_full
 * @property string|null $description
 * @property int|null $accept_send
 * @property int|null $type_id
 *
 * @property-read array $types
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 */
final class GroupModel extends BaseModel
{
    public $code;
    public $name;
    public $name_full;
    public $description;
    public $accept_send;
    public $type_id;

    public readonly array $types;

    public function __construct(GroupEntity $entity, $config = [])
    {
        $this->types = GroupTypeRepository::getAll(
            asArray: true
        );

        parent::__construct($entity, $config);
    }

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
            ['code', 'string', 'length' => [1,10], 'message' => Yii::t('models_error', 'Длина от 2 до 6 символов')],
            ['name', 'required', 'message' => Yii::t('models_error', 'Название обязательно')],
            ['name', 'string', 'length' => [4,64], 'message' => Yii::t('models_error', 'Длина от 4 до 64 символов')],
            [
                'name',
                'unique', 'targetClass' => GroupEntity::class,
                'filter' =>  $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Название не уникально')
            ],
            ['name_full', 'string', 'length' => [4,255], 'message' => Yii::t('models_error', 'Длина от 4 до 255 символов')],
            ['type_id', 'integer'],
            ['type_id', 'in', 'range' => array_keys($this->types), 'message' => Yii::t('models_error', 'Тип группы не соответствует определенным в системе')],
            ['accept_send', 'integer'],
            ['description', 'string']
        ];
    }

    public function attributeLabels(): array
    {
        return GroupHelper::labels();
    }
}