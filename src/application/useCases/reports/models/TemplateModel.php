<?php

namespace app\useCases\reports\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

use app\components\base\BaseModel;
use app\helpers\{
    CommonHelper,
    HtmlPurifier
};
use app\useCases\reports\{
    entities\ReportFormTemplateEntity,
    repositories\ConstantRepository,
    repositories\ConstantruleRepository,
    repositories\ReportRepository,
    helpers\TemplateHelper
};
use app\useCases\users\{
    repositories\group\GroupRepository,
    helpers\RbacHelper
};

/**
 * @property string $name
 * @property int $report_id
 * @property int $use_appg
 * @property int $use_grouptype
 * @property int $form_datetime
 * @property int $form_type
 * @property int $form_usejobs
 * @property int|null $table_type
 * @property string|null $table_rows
 * @property string|null $table_columns
 * @property string|null $table_template
 * @property int $limit_maxfiles
 * @property int $limit_maxsavetime
 *
 * @property UploadedFile $uploadedFile
 * @property-read string|null $oldTemplate
 *
 * @property-read array $groups
 * @property-read array $groupsType
 * @property-read array $reports
 * @property-read array $mergeConstantAndRules
 * @private array $groupsCanSent
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\models\report
 */
final class TemplateModel extends BaseModel
{
    public $name;
    public $report_id;
    public $use_appg;
    public $use_grouptype;
    public $form_datetime;
    public $form_type;
    public $form_usejobs;
    public $table_type;
    public $table_rows;
    public $table_columns;
    public $table_template;
    public int $limit_maxfiles = 100;
    public int $limit_maxsavetime = 864000;

    public $uploadedFile;
    public $oldTemplate;

    public readonly array $groups;
    public readonly array $reports;
    public readonly array $mergeConstantAndRules;

    private readonly array $groupsCanSent;

    public function __construct(ReportFormTemplateEntity $entity, $config = [])
    {
        $this->groups = RbacHelper::getAllowGroupsArray('structure.list.all');
        $this->groupsCanSent = GroupRepository::getAllBy(
            condition: ['id' => array_keys($this->groups), 'accept_send' => 1],
            asArray: true
        );
        $this->reports = ReportRepository::getAllow(
            groups: $this->groups
        );

        $constants = ConstantRepository::getAllow(
            reports: $this->reports,
            groups: $this->groups
        );
        $constantsRule = ConstantruleRepository::getAllow(
            reports: $this->reports,
            groups: $this->groups
        );

        $this->mergeConstantAndRules = ArrayHelper::merge($constants, $constantsRule);
        $this->oldTemplate = null;

        parent::__construct($entity, $config);
    }

    public function init()
    {
        if ( $this->table_rows ) {
            $this->table_rows = CommonHelper::explodeField($this->table_rows);
        }

        if ( $this->table_columns ) {
            $this->table_columns = CommonHelper::explodeField($this->table_columns);
        }

        parent::init();
    }

    public function rules(): array
    {
        return [
            [['name', 'report_id', 'form_type'], 'required'],
            ['name', 'string', 'length' => [4, 64]],
            ['name', 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
            [
                'name',
                'unique',
                'targetClass' => ReportFormTemplateEntity::class, 'targetAttribute' => 'name',
                'filter' => $this->getUniqueFilterString(true)
            ],
            [['report_id', 'form_datetime', 'form_type', 'form_usejobs', 'use_grouptype', 'limit_maxfiles', 'limit_maxsavetime', 'table_type'], 'integer'],

            ['report_id', 'in', 'range' => array_keys($this->reports)],

            ['form_datetime', 'in', 'range' => ReportFormTemplateEntity::REPORT_DATETIMES],
            [
                'form_datetime',
                'default',
                'value' => ReportFormTemplateEntity::REPORT_DATETIME_PERIOD,
                'when' => fn ($model) => $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE
            ],
            ['form_datetime', 'required'],


            ['form_type', 'in', 'range' => ReportFormTemplateEntity::REPORT_TYPES],

            [
                'table_type',
                'required',
                'when' => function($model) {
                    return (
                        $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC &&
                        $model->form_datetime == ReportFormTemplateEntity::REPORT_DATETIME_PERIOD
                    );
                },
                'whenClient' => 'function(attribute, value) {
                    return (
                        $("#templatemodel-form_type").val() == "' . ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC . '" && 
                        $("#templatemodel-form_datetime").val() == "' . ReportFormTemplateEntity::REPORT_DATETIME_PERIOD . '"
                    );
                }',
            ],
            [
                'table_columns',
                'required',
                'when' => fn ($model) => $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC,
                'whenClient' => 'function(attribute, value) {
                    return ($("#templatemodel-form_type").val() == "' . ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC . '" );
                }',
                'message' => Yii::t('models_error', 'Укажите колонки для формирования таблицы'),
            ],
            [
                'table_rows',
                'required',
                'when' => fn ($model) => $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC,
                'whenClient' => 'function(attribute, value) {
                    return ($("#templatemodel-form_type").val() == "' . ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC . '" );
                }',
                'message' => Yii::t('models_error', 'Укажите строки для расчета показателей относительно колонок'),
            ],
            [['table_columns', 'table_rows'], 'checkDynamicValues'],

            [['table_template', 'oldTemplate'], 'string', 'max' => 255],
            [
                'uploadedFile',
                'file',
                'maxFiles' => 1, 'maxSize' => 1024 * 1024 * 10,
                'extensions' => ['ods', 'xls', 'xlsx'],
                'checkExtensionByMimeType' => true,
                'skipOnEmpty' => false,
                'mimeTypes' => [
                    'application/vnd.oasis.opendocument.spreadsheet',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel.sheet.macroEnabled.12',
                ],
                'when' => function ($model) {
                    return $model->form_type == ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE && !$model->isNewEntity;
                },
                'whenClient' => 'function (attribute, value) {
                    return ($("#templatemodel-form_type").val() == "' . ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE .'" && ' . (!$this->isNewEntity ? 'false' : 'true') . ');
                }'
            ],

            ['use_appg', 'default', 'value' => 0]
        ];
    }

    public function attributeLabels(): array
    {
        return TemplateHelper::labels();
    }

    public function checkDynamicValues($attribute)
    {
        if (
            $this->table_type
            && $this->{$attribute}
        ) {
            $elements = match ($this->table_type) {
                ReportFormTemplateEntity::REPORT_TABLE_TYPE_GROUP => $this->groups,
                ReportFormTemplateEntity::REPORT_TABLE_TYPE_CONST => $this->mergeConstantAndRules
            };

            foreach ($this->{$attribute} as $element) {
                if ( !in_array($element, array_keys($elements)) ) {
                    $this->addError('table_columns', Yii::t('models_error', 'Одно из значений не может быть выбрано и использовано'));
                }
            }
        }
    }

    public function afterValidate()
    {
        parent::afterValidate();

        if ( $this->form_type == ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE ) {
            foreach (['table_type', 'table_rows', 'table_columns'] as $attribute) {
                $this->{$attribute} = null;
            }
        }
    }
}