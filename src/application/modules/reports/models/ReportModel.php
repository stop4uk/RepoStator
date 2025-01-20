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
    entities\ReportEntity,
    helpers\ReportHelper
};
use app\modules\users\{
    repositories\GroupRepository,
    helpers\RbacHelper
};

/**
 * @property string $name
 * @property string|null $description
 * @property array|null $groups_only
 * @property array|null $groups_required
 * @property int|null $left_period
 * @property int|null $block_minutes
 * @property int|null $null_day
 *
 * @property-read array $groups;
 * @private array $groupsCanSent
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\models\report
 */
final class ReportModel extends BaseModel
{
    public $name;
    public $description;
    public $groups_only;
    public $groups_required;
    public $left_period;
    public $block_minutes;
    public $null_day;

    public readonly array $groups;
    public readonly array $groupsCanSent;

    public function __construct(ReportEntity $entity, $config = [])
    {
        $this->groups = RbacHelper::getAllowGroupsArray('report.list.all');
        $this->groupsCanSent = GroupRepository::getAllBy(
            condition: ['id' => array_keys($this->groups), 'accept_send' => 1],
            asArray: true
        );

        parent::__construct($entity, $config);
    }

    public function init()
    {
        if ( $this->groups_only ) {
            $this->groups_only = CommonHelper::explodeField($this->groups_only);
        }

        if ( $this->groups_required ) {
            $this->groups_required = CommonHelper::explodeField($this->groups_required);
        }

        if ( $this->description ) {
            $this->description = Json::decode($this->description);
        }

        parent::init();
    }

    public function rules(): array
    {
        return [
            ['name', 'required', 'message' => Yii::t('models_error', 'Укажите название')],
            ['name', 'string', 'length' => [2, 64], 'message' => Yii::t('models_error', 'Длина от 2 до 64 сиволов')],
            [
                'name',
                'unique',
                'targetClass' => ReportEntity::class,
                'filter' => $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Название не уникально')
            ],

            ['description', 'string'],

            [
                ['groups_only', 'groups_required'],
                'each', 'rule' => [
                    'integer',
                    'message' => Yii::t('models_error', 'Допускатеся указание только ID группы')
                ]
            ],
            [
                ['groups_required', 'groups_only'],
                'each', 'rule' => [
                    'in', 'range' => array_keys($this->groups),
                    'message' => Yii::t('models_error', 'В списке указаны группы, которые не находятся в Вашем подчинении или, не могут передавать сведения')
                ],
            ],
            [['groups_required', 'groups_only'], 'each', 'rule' => ['checkCanSentList']],
            ['left_period', 'integer', 'min' => 10, 'tooSmall' => Yii::t('models_error', 'Минимальное разрграничение передачи 10 минут')],
            ['left_period', 'required', 'when' => fn($model) => $model->block_minutes, 'whenClient' => 'function(attribute, value) { return ($("#reportmodel-block_minutes").val().length != 0); }'],
            ['block_minutes', 'integer', 'min' => 5, 'tooSmall' => Yii::t('models_error', 'Минимальное время ограничения 5 минут')],
            [
                'block_minutes',
                'compare', 'compareAttribute' => 'left_period',
                'operator' => '<=',
                'type' => 'number',
                'message' => Yii::t('models_error', 'Блок передачи отчета не может превышать перерыв между отчетами')
            ],
            ['null_day', 'integer'],
            ['null_day', 'default', 'value' => 0],

            [['name', 'description'], 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return ReportHelper::labels();
    }

    public function checkCanSentList()
    {
        if ( $this->groups_only ) {
            foreach ($this->groups_only as $group) {
                if (
                    !in_array($group, array_keys($this->groupsCanSent))
                    && isset($this->groups[$group])
                ) {
                    $this->addError('groups_only', Yii::t('models_error', 'Одна из групп, для которой ' .
                        'предназначен отчет, а именно "{name}", не может передавать сведения. Следовательно, данный отчет ' .
                        'нельзя ей ограничивать', ['name' => $this->groups[$group]]));

                    break;
                }
            }
        }

        if ( $this->groups_required ) {
            foreach ($this->groups_required as $group) {
                if (
                    !in_array($group, array_keys($this->groupsCanSent))
                    && isset($this->groups[$group])
                ) {
                    $this->addError('groups_only', Yii::t('models_error', 'Одна из групп, для которой ' .
                        'выставлено обязательство передачи сведений, а именно "{name}", не может их передавать. Следовательно, ' .
                        'нельзя указывать ее в качестве требования передачи', ['name' => $this->groups[$group]]));

                    break;
                }
            }
        }
    }
}