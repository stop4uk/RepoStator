<?php

namespace app\modules\reports\models;

use Yii;

use app\components\base\BaseModel;
use app\helpers\HtmlPurifier;
use app\modules\reports\{
    entities\ReportConstantEntity,
    entities\ReportConstantRuleEntity,
    helpers\ConstantRuleHelper,
    repositories\ConstantRepository,
    repositories\ReportRepository
};
use app\modules\users\{
    components\rbac\RbacHelper,
    repositories\GroupRepository
};

/**
 * @property string $record
 * @property string $name
 * @property string $description
 * @property string $rule
 * @property int|null $report_id
 * @property array|null $groups_only
 *
 * @property array $groups
 * @property array $reports
 * @property array $constants
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\models
 */
final class ConstantRuleModel extends BaseModel
{
    public $record;
    public $name;
    public $description;
    public $rule;
    public $report_id;
    public $groups_only;

    public array $groups;
    public array $reports;
    public array $constants;
    private readonly array $groupsAllow;

    public function __construct(ReportConstantRuleEntity $entity, array $config = [])
    {
        $groups = [];
        $groupsAllow = RbacHelper::getAllowGroupsArray('constantRule.list.all');
        $groupsCanSent = GroupRepository::getAllBy(
            condition: ['id' => array_keys($groupsAllow), 'accept_send' => 1],
            asArray: true
        );

        if ($entity->id && $entity->report_id) {
            $reportInformation = ReportRepository::get($entity->report_id);
            $reports  = [$entity->report_id => $entity->report_id];


            if ($reportInformation && $reportInformation->groups_only) {
                $groups = array_filter($groupsCanSent, function($key) use ($reportInformation) {
                    return (
                        (!is_array($reportInformation->groups_only) && $key == $reportInformation->groups_only)
                        || in_array($key, $reportInformation->groups_only)
                    );
                }, ARRAY_FILTER_USE_KEY);
            }
        } else {
            $groups = $groupsCanSent;
            $reports = ReportRepository::getAllow(
                groups: $groups
            );
        }

        $this->groups = $groups;
        $this->groupsAllow = $groupsAllow;
        $this->reports = ReportRepository::getAllow(
            groups: $this->groupsAllow
        );

        $this->constants = ConstantRepository::getAllow(
            reports: $reports,
            groups: $groupsAllow
        );

        parent::__construct($entity, $config);
    }

    public function init(): void
    {
        if (!$this->isNewEntity && $this->groups_only) {
            $reportData = ReportRepository::get($this->report_id);
            if ($reportData->groups_only) {
                foreach ($this->groups as $group => $name) {
                    if (
                        (!is_array($reportData->groups_only) && $reportData->groups_only != $group)
                        ||  !in_array($group, $reportData->groups_only)
                    ) {
                        unset($this->groups[$group]);
                    }
                }
            }
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
                'targetClass' => ReportConstantRuleEntity::class,
                'filter' => $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Идентификатор не уникален')
            ],
            [
                'record',
                'unique',
                'targetClass' => ReportConstantEntity::class,
                'filter' => $this->getUniqueFilterString(),
                'message' => Yii::t('models_error', 'Такой же идентификатор присутствует в константах')
            ],

            ['name', 'required', 'message' => Yii::t('models_error', 'Название обязательно. Оно отображается в поиске')],
            ['name', 'string', 'length' => [4, 64], 'message' => Yii::t('models_error', 'Длина от 4 до 64 символов')],
            [
                'name',
                'unique',
                'targetClass' => ReportConstantRuleEntity::class,
                'filter' => $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Название не уникально')
            ],
            [
                'name',
                'unique',
                'targetClass' => ReportConstantEntity::class,
                'filter' => $this->getUniqueFilterString(),
                'message' => Yii::t('models_error', 'Такое же название присутствует в константах')
            ],

            ['rule', 'required', 'message' => Yii::t('models_error', 'Математическое правило обязательно для указания')],
            ['rule', 'string'],
            ['rule', 'checkRule'],

            ['description', 'string'],

            ['report_id', 'integer', 'message' => Yii::t('models_error', 'ID отчета обязательно должно быть числом')],
            ['report_id', 'in', 'range' => array_keys($this->reports)],

            ['groups_only', 'each', 'rule' => ['integer', 'message' => Yii::t('models_error', 'ID группы обязательно должно быть числом')]],
            ['groups_only', 'each', 'rule' => ['in', 'range' => array_keys($this->groups)]],
            ['groups_only', 'each', 'rule' => ['checkCanSentList']],

            ['rule', 'filter', 'filter' => fn($value) => str_replace(' ', '', $value)],
            [['name', 'record', 'description', 'rule'], 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return ConstantRuleHelper::labels();
    }

    public function checkRule($attribute): void
    {
        preg_match_all('/\"(.*?)\"/', $this->rule, $matchConstants);

        if (!$matchConstants[1]) {
            $this->addError($attribute, Yii::t('models_error', 'В математическом правиле отсутствуют ' .
                'константы, которые указаны в кавычках, или, само математическое правило неверно'));
        } else {
            $constantForCheck = match((bool)$this->report_id) {
                true => ConstantRepository::getAllow(
                    reports: [$this->report_id => $this->report_id],
                    groups: $this->groupsAllow
                ),
                false => $this->constants
            };

            foreach ($matchConstants[1] as $constant) {
                if (!in_array($constant, array_keys($constantForCheck))) {
                    $this->addError($attribute, Yii::t('models_error', 'В правиле присутствуют константы, ' .
                        'которые не могут быть Вами использованы, или не могут работать с выбранными для расчета отчетами. ' .
                        'Например, "<strong>{name}</strong>"', ['name' => $constant]));
                }
            }
        }
    }

    public function checkCanSentList(): void
    {
        if ($this->groups_only) {
            foreach ($this->groups_only as $group) {
                if (!in_array($group, array_keys($this->groups))) {
                    $this->addError('groups_only', Yii::t('models_error', 'Одна из групп, для которой ' .
                        'предназначено данное правило, а именно "{name}", не может передавать сведения. Следовательно, данное ' .
                        'правило для нее указывать нельзя', ['name' => $this->groups[$group]]));

                    break;
                }
            }
        }
    }
}