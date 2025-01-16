<?php

namespace app\useCases\users\entities\group;

use yii\behaviors\{
    BlameableBehavior,
    TimestampBehavior
};
use yii\helpers\Json;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

use app\components\base\BaseAR;
use app\useCases\users\{
    models\group\GroupModel,
    helpers\group\GroupHelper
};


/**
 * @property string|null $code
 * @property string $name
 * @property string|null $name_full
 * @property string|null $description
 * @property int|null $type_id
 * @property int|null $accept_send
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 * @property int|null $updated_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\group
 */
final class GroupEntity extends BaseAR
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => time(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_uid'],
                    self::EVENT_BEFORE_UPDATE => ['updated_uid'],
                ],
            ],
            [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'record_status' => self::RSTATUS_DELETED
                ],
            ]
        ];
    }

    public function scenarios(): array
    {
        return [
            self::SCENARIO_CHANGE_RECORD_STATUS => ['record_status'],
            self::SCENARIO_DEFAULT => ['code', 'name', 'name_full', 'description', 'type_id', 'accept_send']
        ];
    }

    public function recordAction(GroupModel $model): void
    {
        $this->setAttributes($model->toArray());
    }

    public function attributeLabels(): array
    {
        return GroupHelper::labels();
    }

    public function beforeSave($insert): bool
    {
        if ( $this->scenario != self::SCENARIO_CHANGE_RECORD_STATUS ) {
            if ( $this->description ) {
                $this->description = Json::encode($this->description);
            }
        }

        return parent::beforeSave($insert);
    }

    public function getType()
    {
        return $this->hasOne(GroupTypeEntity::class, ['id' => 'type_id']);
    }

    public static function tableName(): string
    {
        return '{{groups}}';
    }
}