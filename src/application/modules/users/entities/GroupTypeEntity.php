<?php

namespace app\modules\users\entities;

use yii\behaviors\{
    BlameableBehavior,
    TimestampBehavior
};
use yii\helpers\Json;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

use app\components\base\BaseAR;
use app\modules\users\{
    models\GroupTypeModel,
    helpers\GroupTypeHelper
};

/**
 * @property string $name
 * @property string|null $description
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 * @property int|null $updated_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\entities
 */
final class GroupTypeEntity extends BaseAR
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
            self::SCENARIO_DEFAULT => ['name', 'description']
        ];
    }

    public function recordAction(GroupTypeModel $model): void
    {
        $this->setAttributes($model->toArray());
    }

    public function attributeLabels(): array
    {
        return GroupTypeHelper::labels();
    }

    public function beforeSave($insert): bool
    {
        if ($this->scenario != self::SCENARIO_CHANGE_RECORD_STATUS) {
            if ($this->description) {
                $this->description = Json::encode($this->description);
            }
        }

        return parent::beforeSave($insert);
    }

    public static function tableName(): string
    {
        return '{{groups_types}}';
    }
}