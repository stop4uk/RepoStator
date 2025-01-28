<?php

namespace app\modules\reports\entities;

use Yii;
use yii\behaviors\{
    AttributeBehavior,
    BlameableBehavior,
    TimestampBehavior
};
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

use app\components\base\BaseAR;
use app\modules\reports\{
    models\DataModel,
    helpers\DataHelper
};
use app\modules\users\{
    entities\GroupEntity,
    entities\UserEntity
};

/**
 * @property int $report_id
 * @property int $report_datetime
 * @property int $group_id
 * @property int $struct_id
 * @property string $content
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 * @property int|null $updated_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\entities
 */
final class ReportDataEntity extends BaseAR
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
                'class' => AttributeBehavior::class,
                'value' => match (Yii::$app instanceof \yii\web\Application) {
                    true => Yii::$app->getUser()->getIdentity()->group,
                    false => null
                },
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_gid'],
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
            self::SCENARIO_INSERT => ['report_id', 'report_datetime', 'group_id', 'struct_id', 'content'],
            self::SCENARIO_UPDATE => ['content']
        ];
    }

    public function recordAction(DataModel $model): void
    {
        $this->setAttributes($model->toArray());
        if ( !$this->report_datetime ) {
            $this->report_datetime = date('Y-m-d H:i:s');
        }
    }

    public function attributeLabels(): array
    {
        return DataHelper::labels();
    }

    public function getReport(): ActiveQuery
    {
        return $this->hasOne(ReportEntity::class, ['id' => 'report_id']);
    }

    public function getStructure(): ActiveQuery
    {
        return $this->hasOne(ReportStructureEntity::class, ['id' => 'struct_id']);
    }

    public function getGroup(): ActiveQuery
    {
        return $this->hasOne(GroupEntity::class, ['id' => 'group_id']);
    }

    public function getCreatedUser(): ActiveQuery
    {
        return $this->hasOne(UserEntity::class, ['id' => 'created_uid']);
    }

    public function getChanges(): ActiveQuery
    {
        return $this->hasMany(ReportDataChangeEntity::class, ['data_id' => 'id'])->with(['createdUser']);
    }

    public function beforeSave($insert): bool
    {
        if ( $this->scenario != self::SCENARIO_CHANGE_RECORD_STATUS ) {
            if ( $this->content ) {
                $this->content = Json::encode($this->content);
            }
        }

        return parent::beforeSave($insert);
    }

    public static function tableName(): string
    {
        return '{{reports_data}}';
    }
}