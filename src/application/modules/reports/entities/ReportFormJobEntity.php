<?php

namespace app\modules\reports\entities;

use Yii;
use yii\behaviors\{
    AttributeBehavior,
    BlameableBehavior,
    TimestampBehavior
};
use yii\db\ActiveQuery;

use app\components\{
    base\BaseAR,
    attachedFiles\AttachFileHelper,
};
use app\modules\reports\{
    helpers\JobHelper,
    events\StatisticEvent,
};
use app\modules\users\entities\UserEntity;

/**
 * @property string $job_id
 * @property int $job_status
 * @property int $report_id
 * @property int $template_id
 * @property string $form_period
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\entities
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
            [['report_id', 'job_status', 'file_size'], 'integer'],
            ['form_period', 'string', 'length' => [10, 30]],
            ['job_id', 'string', 'length' => [2, 32]],
            ['job_status', 'in', 'range' => self::STATUSES],
            ['job_status', 'default', 'value' => self::STATUS_WAIT],
            ['storage', 'in', 'range' => array_keys(AttachFileHelper::getStorageName(asList: true))],
            [['file_name', 'file_path', 'file_mime'], 'string', 'max' => 255],
            ['file_hash', 'string', 'max' => 32],
            ['file_extension', 'string', 'max' => 4],
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
        return '{{%reports_form_jobs}}';
    }

    public function setComplete(array $fileData, string $formPeriod): void
    {
        $this->job_status = self::STATUS_COMPLETE;
        foreach($fileData as $attribute => $value) {
            $this->{$attribute} = $value;
        }

        if (
            $this->save(logCategory: 'Reports.Jobs')
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
            $this->save(logCategory: 'Reports.Jobs')
            && Yii::$app->settings->get('report', 'notification_tError')
        ) {
            $this->trigger(self::EVENT_AFTER_ERROR, new StatisticEvent([
                'jobEntity' => $this,
                'template' => $this->template,
            ]));
        }
    }
}