<?php

namespace app\models\report;

use app\components\base\BaseModel;
use app\entities\report\{ReportConstantEntity, ReportConstantRuleEntity};
use app\helpers\{CommonHelper, HtmlPurifier, RbacHelper, report\ConstantRuleHelper};
use app\repositories\{group\GroupBaseRepository, report\ConstantBaseRepository, report\ReportBaseRepository};
use Yii;
use yii\helpers\Json;

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
 * @package app\models\report
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

    public function __construct(ReportConstantRuleEntity $entity, array $config = [])
    {
        $groupsAllow = RbacHelper::getAllowGroupsArray('constantRule.list.all');
        $groupsCanSent = GroupBaseRepository::getAllBy(
            condition: ['id' => array_keys($groupsAllow), 'accept_send' => 1],
            asArray: true
        );

        if ( !$entity->id !== null && $entity->report_id ) {
            $reportInformation = ReportBaseRepository::get($entity->report_id);
            $reports  = [$entity->report_id => $entity->report_id];


            if ( $reportInformation && $reportInformation->groups_only) {
                $groups = array_filter($groupsCanSent, function($key) use ($reportInformation) {
                    return in_array($key, CommonHelper::explodeField($reportInformation->groups_only));
                }, ARRAY_FILTER_USE_KEY);
            }
        } else {
            $groups = $groupsCanSent;
            $reports = ReportBaseRepository::getAllow(
                groups: $groups
            );
        }

        $this->groups = $groups;

        $this->reports = ReportBaseRepository::getAllow(
            groups: $groupsAllow
        );

        $this->constants = ConstantBaseRepository::getAllow(
            reports: $reports,
            groups: $groupsAllow
        );

        parent::__construct($entity, $config);
    }

    public function init()
    {
        if ( $this->groups_only ) {
            $this->groups_only = CommonHelper::explodeField($this->groups_only);
        }

        if ( $this->description ) {
            $this->description = Json::decode($this->description);
        }

        if ( !$this->isNewEntity && $this->groups_only) {
            $reportData = ReportBaseRepository::get($this->report_id);
            if ( $reportData->groups_only ) {
                foreach ($this->groups as $group => $name ) {
                    if ( !in_array($group, CommonHelper::explodeField($reportData->groups_only)) ) {
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

    public function checkRule($attribute)
    {
        preg_match_all('/\"(.*?)\"/', $this->rule, $matchConstants);

        if ( !$matchConstants[1] ) {
            $this->addError($attribute, Yii::t('models_error', 'В математическом правиле отсутствуют ' .
                'константы, которые указаны в кавычках, или, само математическое правило неверно'));
        } else {
            $constantForCheck = match( (bool)$this->report_id) {
                true => ConstantBaseRepository::getAllow(
                    reports: [$this->report_id => $this->report_id],
                    groups: $this->groups
                ),
                false => $this->constants
            };

            foreach ($matchConstants[1] as $constant) {
                if ( !in_array($constant, array_keys($constantForCheck)) ) {
                    $this->addError($attribute, Yii::t('models_error', 'В правиле присутствуют константы, ' .
                        'которые не могут быть Вами использованы, или не могут работать с выбранными для рассчета отчетами. ' .
                        'Например, "<strong>{name}</strong>"', ['name' => $constant]));
                }
            }
        }
    }

    public function checkCanSentList()
    {
        if ( $this->groups_only ) {
            foreach ($this->groups_only as $group) {
                if ( !in_array($group, array_keys($this->groups)) ) {
                    $this->addError('groups_only', Yii::t('models_error', 'Одна из групп, для которой ' .
                        'предназначено данное правило, а именно "{name}", не может передавать сведения. Следовательно, данное ' .
                        'правило для нее указаывать нельзя', ['name' => $this->groups[$group]]));

                    break;
                }
            }
        }
    }
}