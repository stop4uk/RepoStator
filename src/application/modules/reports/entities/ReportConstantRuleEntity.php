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
use app\helpers\CommonHelper;
use app\modules\reports\{
    models\ConstantRuleModel,
    helpers\ConstantRuleHelper
};

/**
 * @property string $record
 * @property string $name
 * @property string|null $description
 * @property string $rule
 * @property int|null $report_id
 * @property string|null $groups_only
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 * @property int|null $updated_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\entities
 */
final class ReportConstantRuleEntity extends BaseAR
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
            self::SCENARIO_INSERT => ['record', 'name', 'description', 'rule', 'report_id', 'groups_only'],
            self::SCENARIO_UPDATE => ['record', 'name', 'description', 'rule', 'report_id', 'groups_only'],
        ];
    }

    public function recordAction(ConstantRuleModel $model): void
    {
        $this->setAttributes($model->toArray());
    }

    public function attributeLabels(): array
    {
        return ConstantRuleHelper::labels();
    }

    public function getReport(): ActiveQuery
    {
        return $this->hasOne(ReportEntity::class, ['id' => 'report_id']);
    }

    public function beforeSave($insert): bool
    {
        if ($this->scenario != self::SCENARIO_CHANGE_RECORD_STATUS) {
            if ($this->description) {
                $this->description = Json::encode($this->description);
            }

            if ($this->groups_only) {
                $this->groups_only = CommonHelper::implodeField($this->groups_only);
            }
        }

        return parent::beforeSave($insert);
    }

    public static function tableName(): string
    {
        return '{{%reports_constant_rules}}';
    }
}