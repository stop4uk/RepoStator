<?php

namespace app\entities\report;

use app\components\base\BaseAR;
use app\helpers\{CommonHelper, report\ReportHelper};
use app\models\report\ReportModel;
use Yii;
use yii\behaviors\{AttributeBehavior, BlameableBehavior, TimestampBehavior};
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * @property string $name
 * @property string|null $description
 * @property array|null $groups_only
 * @property array|null $groups_required
 * @property int|null $left_period
 * @property int|null $block_minutes
 * @property int|null $null_day
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 * @property int|null $updated_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\report
 */
final class ReportEntity extends BaseAR
{
    public $canAddedFor;
    public $timePeriod;

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
            self::SCENARIO_DEFAULT => ['name', 'description', 'groups_only', 'groups_required', 'left_period', 'block_minutes', 'null_day']
        ];
    }

    public function recordAction(ReportModel $model): void
    {
        $this->setAttributes($model->toArray());
    }

    public function attributeLabels(): array
    {
        return ReportHelper::labels();
    }

    public function beforeSave($insert): bool
    {
        if ( $this->scenario != self::SCENARIO_CHANGE_RECORD_STATUS ) {
            if ( $this->description ) {
                $this->description = Json::encode($this->description);
            }

            if ( $this->groups_only ) {
                $this->groups_only = CommonHelper::implodeField($this->groups_only);
            }

            if ( $this->groups_required ) {
                $this->groups_required = CommonHelper::implodeField($this->groups_required);
            }
        }

        return parent::beforeSave($insert);
    }

    public function getData(): ActiveQuery
    {
        return $this->hasMany(ReportDataEntity::class, ['report_id' => 'id'])
            ->andFilterWhere(['record_status' => ReportDataEntity::RSTATUS_ACTIVE]);
    }

    public static function tableName(): string
    {
        return '{{reports}}';
    }
}