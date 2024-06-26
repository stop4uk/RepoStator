<?php

namespace app\entities\report;

use Yii;
use yii\behaviors\{
    AttributeBehavior,
    BlameableBehavior,
    TimestampBehavior
};
use yii\helpers\Json;
use yii\db\ActiveQuery;

use app\base\BaseAR;
use app\entities\user\UserEntity;
use app\helpers\report\DataChangeHelper;

/**
 * @property int $report_id
 * @property int $data_id
 * @property string $content
 * @property int $created_at
 * @property int $created_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\report
 */
final class ReportDataChangeEntity extends BaseAR
{
    const OPERATION_ADD = 0;
    const OPERATION_EDIT = 1;
    const OPERATION_DELETE = 2;

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => time(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_uid'],
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
        ];
    }

    public function rules(): array
    {
        return [
            [['report_id', 'data_id', 'content'], 'required'],
            [['report_id', 'data_id'], 'integer'],
            ['data_id', 'exist', 'targetClass' => ReportDataEntity::class, 'targetAttribute' => 'id'],
            ['report_id', 'exist', 'targetClass' => ReportEntity::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels(): array
    {
        return DataChangeHelper::labels();
    }

    public function getReport(): ActiveQuery
    {
        return $this->hasOne(ReportEntity::class, ['id' => 'report_id']);
    }

    public function getData(): ActiveQuery
    {
        return $this->hasOne(ReportDataEntity::class, ['id' => 'data_id']);
    }

    public function getCreatedUser(): ActiveQuery
    {
        return $this->hasOne(UserEntity::class, ['id' => 'created_uid']);
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
        return '{{reports_data_changes}}';
    }
}