<?php

namespace app\forms\report;

use Yii;
use yii\base\Model;
use yii\validators\DateValidator;

use app\entities\report\ReportEntity;
use app\repositories\{
    group\GroupRepository,
    report\ReportRepository,
    report\DataRepository
};
use app\helpers\RbacHelper;

/**
 * @property int $group
 * @property int $report
 * @property string $period
 *
 * @property-read array $groups
 * @property-read array $reports
 * @private ReportEntity $reportData
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\forms\report
 */
final class ControlCreateForForm extends Model
{
    public $group;
    public $report;
    public $period;

    public readonly array $groups;
    public array $reports = [];

    private ReportEntity $reportData;

    public function __construct($config = [])
    {
        $groups = RbacHelper::getAllowGroupsArray('data.edit.all');
        $this->groups = GroupRepository::getAllBy(
            condition: ['id' => array_keys($groups), 'accept_send' => 1],
            asArray: true
        );

        parent::__construct($config);
    }

    public function init()
    {
        if ( !$this->reports ) {
            $this->reports = ReportRepository::getAllow(
                groups: $this->groups
            );
        }

        parent::init();
    }

    public function rules(): array
    {
        return [
            [['group', 'report', 'period'], 'required'],
            [['group', 'report'], 'integer'],
            ['period', 'validatePeriod'],
            ['report', 'in', 'range' => array_keys($this->reports)],
            ['report', 'checkSend'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'group' => Yii::t('models', 'Группа'),
            'report' => Yii::t('models', 'Отчет'),
            'period' => Yii::t('models', 'Период передачи')
        ];
    }

    public function validatePeriod($attribute, $value)
    {
        if ( $value ) {
            $validator = new DateValidator();
            $validator->format = 'php:d.m.Y';

            if ( $this->reportData !== null && $this->reportData->left_period ) {
                $validator->format = 'php:d.m.Y H:i';
            }

            if ( !$validator->validate($value) ) {
                $this->addError($attribute, Yii::t('models_error', 'Формат даты не соотвествует отчету'));
            }
        }
    }

    public function checkSend($attribute)
    {
        if ( !$this->hasErrors() && $this->reportData->left_period ) {
            $query = DataRepository::getBy([
                'group_id' => $this->group,
                'report_id' => $this->report,
                'report_datetime' => $this->period
            ], [], true);

            if ( $query ) {
                $this->addError($attribute, Yii::t('models_error', 'За данный период отчет уже передан'));
            }
        }
    }

    public function beforeValidate()
    {
        if ( $this->report ) {
            $this->reportData = ReportRepository::get($this->report);
        }

        parent::beforeValidate();
    }
}