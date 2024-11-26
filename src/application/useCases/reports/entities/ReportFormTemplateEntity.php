<?php

namespace app\useCases\reports\entities;

use Yii;
use yii\behaviors\{
    AttributeBehavior,
    BlameableBehavior,
    TimestampBehavior
};
use yii\db\{
    ActiveQuery,
    Expression
};
use yii2tech\ar\softdelete\SoftDeleteBehavior;

use app\components\base\BaseAR;
use app\helpers\CommonHelper;
use app\useCases\reports\{
    models\TemplateModel,
    helpers\TemplateHelper,
};

/**
 * @property int $report_id
 * @property string $name
 * @property int $form_datetime
 * @property int $form_type
 * @property int $form_usejobs
 * @property int|null $use_appg
 * @property int|null $use_grouptype
 * @property int|null $table_type
 * @property string|null $table_rows
 * @property string|null $table_columns
 * @property string|null $table_template
 * @property int $limit_maxfiles
 * @property int $limit_maxsavetime
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 * @property int|null $updated_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\report
 */
final class ReportFormTemplateEntity extends BaseAR
{
    const REPORT_TYPE_DYNAMIC = 0;
    const REPORT_TYPE_TEMPLATE = 1;

    const REPORT_TABLE_TYPE_CONST = 0;
    const REPORT_TABLE_TYPE_GROUP = 1;

    const REPORT_DATETIME_WEEK = 0;
    const REPORT_DATETIME_MONTH = 1;
    const REPORT_DATETIME_PERIOD = 2;

    const REPORT_TYPES = [
        self::REPORT_TYPE_DYNAMIC,
        self::REPORT_TYPE_TEMPLATE
    ];

    const REPORT_DATETIMES = [
        self::REPORT_DATETIME_WEEK,
        self::REPORT_DATETIME_MONTH,
        self::REPORT_DATETIME_PERIOD
    ];

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
            self::SCENARIO_INSERT => [
                'report_id', 'name', 'use_appg', 'use_grouptype', 'form_datetime', 'form_type',
                'form_usejobs', 'table_type', 'table_rows', 'table_columns', 'table_template', 'limit_maxfiles',
                'limit_maxsavetime'
            ],
            self::SCENARIO_UPDATE => [
                'name', 'use_appg', 'use_grouptype', 'form_datetime', 'form_usejobs', 'table_type', 'table_rows',
                'table_columns', 'table_template', 'limit_maxfiles', 'limit_maxsavetime'
            ]
        ];
    }

    public function recordAction(TemplateModel $model): void
    {
        $this->setAttributes($model->toArray());
    }

    public function attributeLabels(): array
    {
        return TemplateHelper::labels();
    }

    public function beforeSave($insert): bool
    {
        if ( $this->scenario != self::SCENARIO_CHANGE_RECORD_STATUS ) {
            if ( $this->table_rows ) {
                $this->table_rows = CommonHelper::implodeField($this->table_rows);
            }

            if ( $this->table_columns ) {
                $this->table_columns = CommonHelper::implodeField($this->table_columns);
            }
        }

        return parent::beforeSave($insert);
    }

    public function getReport(): ActiveQuery
    {
        return $this->hasOne(ReportEntity::class, ['id' => 'report_id']);
    }

    public function getResultFiles(): ActiveQuery
    {
        return $this->hasMany(ReportFormJobEntity::class, ['template_id' => 'id'])
            ->andFilterWhere(['=', 'job_status', ReportFormJobEntity::STATUS_COMPLETE])
            ->andFilterWhere([
                'or',
                ['is not', 'file', new Expression('null')],
                ['!=', 'file', new Expression("''")],
            ]);
    }

    public static function tableName(): string
    {
        return '{{reports_form_templates}}';
    }
}