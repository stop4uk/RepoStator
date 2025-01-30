<?php

namespace app\modules\reports\models;

use Yii;
use yii\helpers\Json;

use app\components\base\BaseModel;
use app\helpers\{
    CommonHelper,
    HtmlPurifier
};
use app\modules\reports\{
    entities\ReportConstantEntity,
    entities\ReportConstantRuleEntity,
    repositories\ReportRepository,
    helpers\ConstantHelper
};
use app\modules\users\helpers\RbacHelper;

/**
 * @property string $record
 * @property string $name
 * @property string|null $name_full
 * @property string|null $description
 * @property string|null $union_rules
 * @property array|null $reports_only
 *
 * @property-read array $reports
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\models
 */
final class ConstantModel extends BaseModel
{
    public $record;
    public $name;
    public $name_full;
    public $description;
    public $union_rules;
    public $reports_only;

    public readonly array $reports;

    public function __construct(ReportConstantEntity $entity, array $config = [])
    {
        $groups = RbacHelper::getAllowGroupsArray('constant.list.all');
        $this->reports = ReportRepository::getAllow(
            groups: $groups
        );

        parent::__construct($entity, $config);
    }

    public function init()
    {
        if ($this->reports_only) {
            $this->reports_only = CommonHelper::explodeField($this->reports_only);
        }

        if ($this->description) {
            $this->description = Json::decode($this->description);
        }

        parent::init();
    }

    public function rules(): array
    {
        return [
            ['record', 'required', 'message' => Yii::t('models_error', 'Идентификатор обязателен')],
            ['record', 'string', 'length' => [2, 32], 'message' => Yii::t('models_error', 'От 2 до 32 символов')],
            ['record', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/i', 'message' => Yii::t('models_error', 'Поле может содержать только латинские буквы, цифры, а также символы подчеркивания и тире')],
            [
                'record',
                'unique',
                'targetClass' => ReportConstantEntity::class,
                'filter' => $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Идентификатор не уникален')
            ],
            [
                'record',
                'unique',
                'targetClass' => ReportConstantRuleEntity::class,
                'filter' => $this->getUniqueFilterString(),
                'message' => Yii::t('models_error', 'Такой же идентификатор присутствует в правилах сложения')
            ],

            ['name', 'required', 'message' => Yii::t('models_error', 'Название обязательно. Оно отображается в поиске')],
            ['name', 'string', 'length' => [4, 64], 'message' => Yii::t('models_error', 'Длина от 4 до 64 символов')],
            [
                'name',
                'unique',
                'targetClass' => ReportConstantEntity::class,
                'filter' => $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Название не уникально')
            ],
            [
                'name',
                'unique',
                'targetClass' => ReportConstantRuleEntity::class,
                'filter' => $this->getUniqueFilterString(),
                'message' => Yii::t('models_error', 'Такое же название присутствует в правилах сложения')
            ],

            ['name_full', 'string', 'length' => [4, 255], 'message' => Yii::t('models_error', 'Длина от 4 до 255 символов')],

            [['description', 'union_rules'], 'string'],

            ['reports_only', 'each', 'rule' => ['integer', 'message' => Yii::t('models_error', 'ID отчета обязательно должно быть числом')]],
            ['reports_only', 'each', 'rule' => ['in', 'range' => array_keys($this->reports), 'message' => Yii::t('models_error', 'Один из указанных отчетов Вам недоступен')]],

            [['name', 'name_full', 'record', 'description', 'union_rules'], 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],

            ['union_rules', 'match', 'pattern' => '~^(\p{L}|\p{N}|\p{Zs}|\p{Sm}|[.])+$~u', 'message' => Yii::t('models_error', 'Допускается указание букв, цифр, пробела, точки и математических символов')],
            ['union_rules', 'match', 'pattern' => '/=/', 'message' => Yii::t('models_error', 'Правило объединения в обязательном порядке должно содержать разделитель в виде символа "="')],
        ];
    }

    public function attributeLabels(): array
    {
        return ConstantHelper::labels();
    }
}