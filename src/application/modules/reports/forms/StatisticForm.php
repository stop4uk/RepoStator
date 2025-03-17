<?php

namespace app\modules\reports\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

use app\modules\{
    reports\entities\ReportFormTemplateEntity,
    reports\repositories\ConstantRepository,
    reports\repositories\ConstantruleRepository,
    reports\repositories\ReportRepository,
    users\components\rbac\RbacHelper,
    users\repositories\GroupRepository
};

/**
 * @property int $report
 * @property int $template
 * @property string $period
 * @property int|null $dynamic_form_type
 * @property array|null $dynamic_row
 * @property array|null $dynamic_column
 * @property int|null $dynamic_use_appg
 * @property int|null $dynamic_use_grouptype
 *
 * @property-read array $reports
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\forms
 */
final class StatisticForm extends Model
{
    public $report;
    public $template;
    public $period;
    public $dynamic_form_type;
    public $dynamic_form_column;
    public $dynamic_form_row;
    public $dynamic_use_appg;
    public $dynamic_use_jobs;
    public $dynamic_use_grouptype;

    public readonly array $reports;

    public function __construct($config = [])
    {
        $groups = RbacHelper::getAllowGroupsArray('report.list.main');
        $this->reports = ReportRepository::getAllow(
            groups: $groups
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['report', 'period'], 'required'],
            [['report', 'template', 'dynamic_form_type', 'dynamic_use_appg', 'dynamic_use_jobs', 'dynamic_use_grouptype'], 'integer'],
            ['report', 'in', 'range' => array_keys($this->reports)],
            [
                'template', 'required',
                'when' => fn($model) => $model->report && ($model->dynamic_form_type == null),
                'whenClient' => 'function(attribute, value) {
                    return (!$("#statisticform-dynamic_form_type").val() && $("#statisticform-report").val());
                }'
            ],
            ['period', 'string'],

            [
                'dynamic_form_type', 'required',
                'when' => fn ($model) => $model->report && ($model->template == null) && $this->getReportData(),
                'whenClient' => 'function(attribute, value) {
                    return (!$("#statisticform-template").val() && $("#statisticform-report").val());
                }'
            ],
            ['dynamic_form_type', 'in', 'range' => ReportFormTemplateEntity::REPORT_TABLE_TYPES],
            [
                ['dynamic_form_row', 'dynamic_form_column'], 'required',
                'when' => fn($model) => ($model->dynamic_form_type != null),
                'whenClient' => 'function(attribute, value) {
                    return ($("#statisticform-dynamic_form_type").val());
                }'
            ],
            [['dynamic_form_row', 'dynamic_form_column'], 'checkDynamicValues'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'report' => Yii::t('models', 'Отчет'),
            'period' => Yii::t('models', 'Период расчета'),
            'template' => Yii::t('models', 'Шаблон'),
            'dynamic_form_type' => Yii::t('entities', 'Тип колонок'),
            'dynamic_form_row' => Yii::t('entities', 'Список строк'),
            'dynamic_form_column' => Yii::t('entities', 'Список колонок'),
            'dynamic_use_appg' => Yii::t('entities', 'С АППГ'),
            'dynamic_use_jobs' => Yii::t('entities', 'Использовать очередь'),
            'dynamic_use_grouptype' => Yii::t('entities', 'Заголовки по типам групп'),
        ];
    }

    public function checkDynamicValues($attribute): void
    {
        if (
            !$this->dynamic_form_type
            || !$this->{$attribute}
        ) {
            return;
        }

        $elements = [];
        $groupsAllow = Yii::$app->getUser()->getIdentity()->groups;
        $groupsCanSent = GroupRepository::getAllBy(['id' => array_keys($groupsAllow), 'accept_send' => 1])->all();
        $groups = ArrayHelper::map($groupsCanSent, 'id', 'name');
        $mergeConstantAndRules = ArrayHelper::merge(
            ConstantRepository::getAllow(reports: [$this->report => $this->report], groups: $groupsAllow),
            ConstantruleRepository::getAllow(reports: [$this->report => $this->report], groups: $groupsAllow)
        );

        switch ($attribute) {
            case 'dynamic_form_column':
                $elements = match ((int)$this->dynamic_form_type) {
                    ReportFormTemplateEntity::REPORT_TABLE_TYPE_GROUP => $groups,
                    ReportFormTemplateEntity::REPORT_TABLE_TYPE_CONST => $mergeConstantAndRules
                };
                break;
            case 'dynamic_form_row':
                $elements = match ((int)$this->dynamic_form_type) {
                    ReportFormTemplateEntity::REPORT_TABLE_TYPE_GROUP => $mergeConstantAndRules,
                    ReportFormTemplateEntity::REPORT_TABLE_TYPE_CONST => $groups
                };
                break;
        }

        if ($elements) {
            foreach ($this->{$attribute} as $element) {
                if (!in_array($element, array_keys($elements))) {
                    $this->addError($attribute, Yii::t('models_error', 'Одно из значений в поле "{nameField}" не может быть выбрано и использовано', ['nameField' => $this->getAttributeLabel($attribute)]));
                    break;
                }
            }
        }
    }

    private function getReportData(): bool
    {
        return (bool)ReportRepository::get($this->report)->allow_dynamicForm;
    }
}