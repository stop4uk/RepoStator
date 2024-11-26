<?php

namespace forms;

use app\helpers\RbacHelper;
use repositories\ReportBaseRepository;
use Yii;
use yii\base\Model;

/**
 * @property int $report
 * @property int $template
 * @property string $period
 *
 * @property-read array $reports
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\forms
 */
final class StatisticForm extends Model
{
    public $report;
    public $template;
    public $period;

    public readonly array $reports;

    public function __construct($config = [])
    {
        $groups = RbacHelper::getAllowGroupsArray('report.list.main');
        $this->reports = ReportBaseRepository::getAllow(
            groups: $groups
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['report', 'template'], 'required'],
            [['report', 'template'], 'integer'],
            ['report', 'in', 'range' => array_keys($this->reports)],
            ['period', 'string']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'report' => Yii::t('models', 'Отчет'),
            'period' => Yii::t('models', 'Период расчета'),
            'template' => Yii::t('models', 'Шаблон')
        ];
    }
}