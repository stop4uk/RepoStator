<?php

namespace app\useCases\reports\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\validators\DateValidator;

use app\components\base\BaseAR;
use app\helpers\CommonHelper;
use app\useCases\reports\{
    entities\ReportDataEntity,
    entities\ReportEntity,
    repositories\ReportBaseRepository,
};
use app\useCases\users\helpers\RbacHelper;

/**
 * @property int $report
 * @property string $period
 * 
 * @property array $reports
 * @private ReportEntity $reportData
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\forms\report
 */
final class ControlCheckFullForm extends Model
{
    public $report;
    public $period;

    public array $reports = [];

    private ReportEntity $reportData;

    public function init()
    {
        if ( !$this->reports ) {
            $groups = RbacHelper::getAllowGroupsArray('data.list.all');
            $this->reports = ReportBaseRepository::getAllow(
                groups: $groups
            );
        }

        parent::init();
    }

    public function rules(): array
    {
        return [
            [['report', 'period'], 'required'],
            ['report', 'integer'],
            ['period', 'validatePeriod'],
            ['report', 'in', 'range' => array_keys($this->reports)],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'report' => Yii::t('models', 'Отчет'),
            'period' => Yii::t('models', 'Период передачи')
        ];
    }

    public function validatePeriod($attribute, $value)
    {
        if ( $value ) {
            $validator = new DateValidator();
            $validator->format = Yii::$app->settings->get('system', 'app_language_date');

            if ($this->reportData->left_period) {
                $validator->format = Yii::$app->settings->get('system', 'app_language_dateTimeMin');
            }

            if ( !$validator->validate($value) ) {
                $this->addError($attribute, Yii::t('models_error', 'Формат даты не соотвествует отчету'));
            }
        }
    }

    public function beforeValidate(): bool
    {
        if ( $this->report ) {
            $this->reportData = ReportBaseRepository::get($this->report);
        }

        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        if ( $this->period ) {
            $this->period = strtotime($this->period);
        }

        parent::afterValidate();
    }
    
    public function getOutList()
    {
        $result = [];

        if ( $this->reportData->groups_required ) {
            $query = ReportDataEntity::find()
                ->where([
                    'record_status' => BaseAR::RSTATUS_ACTIVE,
                    'group_id' => array_keys(Yii::$app->getUser()->getIdentity()->groups)
                ]);

            if ( $this->reportData->left_period ) {
                $query->andFilterWhere(['BETWEEN', 'report_datetime', $this->period, ($this->period + ($this->reportData->left_period * 60))]);
            } else {
                $query->andFilterWhere(['=', 'DATE_FORMAT(FROM_UNIXTIME(report_datetime), "%Y-%m-%d")', date('Y-m-d', $this->period)]);
            }

            if ( $resultQuery = $query->all() ) {
                $allowGroups = Yii::$app->getUser()->getIdentity()->groups;
                $requiredGroups = CommonHelper::explodeField($this->reportData->groups_required);
                $haveReports = ArrayHelper::map($resultQuery, 'id', 'group_id');

                foreach ( $requiredGroups as $group ) {
                    if ( !in_array($group, $haveReports) &&  isset($allowGroups[$group]) ) {
                        $result[] = $allowGroups[$group];
                    }
                }
            }
        }

        return $result;
    }
}