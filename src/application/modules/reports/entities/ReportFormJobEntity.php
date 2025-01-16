<?php

namespace app\useCases\reports\entities;

use app\components\{base\BaseAR};
use app\helpers\CommonHelper;
use app\useCases\reports\events\StatisticEvent;
use app\useCases\reports\helpers\JobHelper;
use app\useCases\users\entities\user\UserEntity;
use Yii;
use yii\behaviors\{AttributeBehavior, BlameableBehavior, TimestampBehavior};
use yii\db\ActiveQuery;

/**
 * @property string $job_id
 * @property int $job_status
 * @property int $report_id
 * @property int $template_id
 * @property string $form_period
 * @property string|null $file
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\report
 */
class ReportFormJobEntity extends BaseAR
{
    const EVENT_AFTER_COMPLETE = 'job.afterComplete';
    const EVENT_AFTER_ERROR = 'job.afterError';

    const STATUS_WAIT = 0;
    const STATUS_COMPLETE = 1;
    const STATUS_ERROR = 2;

    const STATUSES = [
        self::STATUS_WAIT,
        self::STATUS_COMPLETE,
        self::STATUS_ERROR
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
            [['report_id', 'job_id'], 'required'],
            [['report_id', 'job_status'], 'integer'],
            ['form_period', 'string', 'length' => [10, 30]],
            ['file', 'string', 'length' => [10, 255]],
            ['job_id', 'string', 'length' => [2, 32]],
            ['job_status', 'in', 'range' => self::STATUSES],
            ['job_status', 'default', 'value' => self::STATUS_WAIT]
        ];
    }

    public function attributeLabels(): array
    {
        return JobHelper::labels();
    }

    public function getReport(): ActiveQuery
    {
        return $this->hasOne(ReportEntity::class, ['id' => 'report_id']);
    }

    public function getTemplate(): ActiveQuery
    {
        return $this->hasOne(ReportFormTemplateEntity::class, ['id' => 'template_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(UserEntity::class, ['id' => 'created_uid']);
    }

    public static function tableName(): string
    {
        return '{{reports_form_jobs}}';
    }

    public function setComplete(string $file, string $formPeriod): void
    {
        $this->job_status = self::STATUS_COMPLETE;
        $this->file = Yii::$app->params['downloadFormFilesAlias'] . DIRECTORY_SEPARATOR . $file;

        if (
            CommonHelper::saveAttempt($this, 'Reports.Jobs')
            && Yii::$app->settings->get('report', 'notification_tComplete')
        ) {
            $this->trigger(self::EVENT_AFTER_COMPLETE, new StatisticEvent([
                'jobEntity' => $this,
                'template' => $this->template,
                'period' => $formPeriod
            ]));
        }
    }

    public function setError(): void
    {
        $this->job_status = self::STATUS_ERROR;
        if (
            CommonHelper::saveAttempt($this, 'Reports.Jobs')
            && Yii::$app->settings->get('report', 'notification_tError')
        ) {
            $this->trigger(self::EVENT_AFTER_ERROR, new StatisticEvent([
                'jobEntity' => $this,
                'template' => $this->template,
            ]));
        }
    }
}