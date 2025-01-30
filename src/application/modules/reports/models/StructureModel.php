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
    entities\ReportStructureEntity,
    repositories\ConstantRepository,
    repositories\ReportRepository,
    repositories\StructureRepository,
    helpers\StructureHelper
};
use app\modules\users\{
    repositories\GroupRepository,
    helpers\RbacHelper
};

/**
 * @property int report_id
 * @property string $name
 * @property array|null $groups_only
 * @property string $content
 * @property int|null $use_union_rules
 * @property array $contentGroups
 * @property array $contentConstants
 * @property array $groups
 *
 * @property-read array $reports
 * @property-read array $constants
 * @private array $groupsCanSent
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\models
 */
final class StructureModel extends BaseModel
{
    public $name;
    public $report_id;
    public $groups_only;
    public $content;
    public $use_union_rules;

    public array $contentGroups = [];
    public array $contentConstants = [];

    public array $groups;
    public readonly array $reports;
    public readonly array $constants;
    public readonly array $groupsCanSent;

    public function __construct(ReportStructureEntity $entity, $config = [])
    {
        $this->groups = RbacHelper::getAllowGroupsArray('structure.list.all');
        $this->groupsCanSent = GroupRepository::getAllBy(
            condition: ['id' => array_keys($this->groups), 'accept_send' => 1],
            asArray: true
        );
        $this->reports = ReportRepository::getAllow(
            groups: $this->groups
        );
        $this->constants = ConstantRepository::getAllow(
            reports: $this->reports,
            groups: $this->groups
        );

        parent::__construct($entity, $config);
    }

    public function init()
    {
        if ($this->content) {
            $arrayData = Json::decode($this->content);
            $this->contentGroups = $arrayData['groups'];
            $this->contentConstants = $arrayData['constants'];
        }

        if ($this->groups_only) {
            $this->groups_only = CommonHelper::explodeField($this->groups_only);
        }

        if (!$this->isNewEntity && $this->groups_only) {
            $reportData = ReportRepository::get($this->report_id);
            if ($reportData->groups_only) {
                foreach ($this->groups as $group => $name) {
                    if (!in_array($group, CommonHelper::explodeField($reportData->groups_only))) {
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
            ['report_id', 'required', 'message' => Yii::t('models_error', 'Укажите отчет')],
            ['report_id', 'integer'],
            ['report_id', 'in', 'range' => array_keys($this->reports), 'message' => Yii::t('models_error', 'Указанный отчет не может быть выбра')],

            ['name', 'required', 'message' => Yii::t('models_error', 'Название обязательно')],
            ['name', 'string', 'length' => [4, 64], 'message' => Yii::t('models_error', 'Длина от 4 до 64 символов')],
            [
                'name',
                'unique',
                'targetClass' => ReportStructureEntity::class,
                'filter' => $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Название не уникально'),
            ],

            ['groups_only', 'checkGroups', 'skipOnEmpty' => false, 'skipOnError' => false],

            ['use_union_rules', 'integer'],

            ['contentGroups', 'checkContentGroups'],
            ['contentConstants', 'checkContentConstants'],

            [['name', 'content'], 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return StructureHelper::labels();
    }

    public function checkGroups($attribute)
    {
        if ($this->groups_only) {
            if ($this->report_id) {
                $reportData = ReportRepository::get($this->report_id);
            }

            foreach ($this->groups_only as $group) {
                if (
                    !in_array($group, array_keys($this->groupsCanSent))
                    && isset($this->groups[$group])
                ) {
                    $this->addError('groups_only', Yii::t('models_error', 'Одна из групп, для которой ' .
                        'предназначена структура, а именно "{name}", не может передавать сведения. Следовательно, нельзя ограничивать ' .
                        'ей структуру', ['name' => $this->groups[$group]]));

                    break;
                }

                if (isset($reportData) && $reportData->groups_only) {
                    if (!in_array($group, CommonHelper::explodeField($reportData->groups_only))) {
                        $this->addError('groups_only', Yii::t('models_error', 'Одна из указанных групп, а именно, ' .
                            '"{name}", не может быть выбрана, так как отчет, для которого строится структура не поддерживает ее.', [
                            'name' => $this->groups[$group]
                        ]));
                    }
                }
            }
        }

        if ($this->report_id) {
            $allowStructures = StructureRepository::getAllow(
                reports: [$this->report_id => $this->report_id],
                groups: $this->groups
            );
            $structuresList = StructureRepository::getAllBy(
                condition: ['id' => array_keys($allowStructures), 'report_id' => $this->report_id]
            )->all();
            $resultQuery = ['empty' => 0, 'withOnly' => []];

            if ($structuresList) {
                foreach ) {$structuresList as $structure) {
                    if (!$structure->groups_only) {
                        $resultQuery['empty']++;
                        continue;
                    }

                    $resultQuery['withOnly'][$structure->id] = $structure->groups_only;
                }

                if (
                    !$this->groups_only
                    && $resultQuery['empty']
                ) {
                    $this->addError($attribute, Yii::t('models_error', 'Вы должны указать конкретные группы ' .
                        'для этой структуры, потому что, для выбранного отчета уже есть структура затрагивающая все группы'));
                }

                if (
                    $this->groups_only
                    && count($resultQuery['withOnly'])
                ) {
                    $haveGroups = [];
                    foreach ($resultQuery['withOnly'] as $structID => $list) {
                        $haveGroups += CommonHelper::explodeField($list);
                    }

                    $haveGroups = array_unique($haveGroups);
                    foreach ($this->groups_only as $group) {
                        if (in_array($group, $haveGroups)) {
                            $this->addError($attribute, Yii::t('models_error', 'Для группы "{name}" уже есть ' .
                                'активная структура', ['name' => $this->groups[$group] ?? $group]));

                            break;
                        }
                    }
                }
            }
        }
    }

    public function checkContentGroups()
    {
        if (count($this->contentGroups) > 1) {
            if (in_array("", $this->contentGroups)) {
                $this->addError("contentGroups", Yii::t('models_error', 'В Вашей структуре несколько ' .
                    'разделов, а значит каждый из них должен быть именован'));
            }

            $uniqueItems = array_unique($this->contentGroups);
            $nonUniqueItems = array_values(array_diff_assoc($this->contentGroups, $uniqueItems));

            if ($nonUniqueItems) {
                $this->addError('contentGroups', Yii::t('models_error', 'Названия разделов в стуктуре ' .
                    'должны быть уникальными'));
            }
        }
    }

    public function checkContentConstants()
    {
        $haveError = false;

        foreach ($this->contentConstants as $constants) {
            if (is_string($constants)) {
                $haveError = true;
                $this->addError("contentConstants", Yii::t('models_error', 'Заполните содержимое ' .
                    'раздела в структуре или, удалите раздел'));

                break;
            }
        }

        if ($haveError) {
            return false;
        }

        foreach ($this->contentConstants as $key => $constants) {
            if (!is_array($constants)) {
                continue;
            }

            foreach ($constants as $constant) {
                if (!is_string($constant)) {
                    $haveError = true;
                    $this->addError("contentConstants", Yii::t('models_error', 'В структуре {id} ' .
                        'присутствует неверная константа', ['id' => $key]));

                    break;
                }
            }

            if (!$haveError && count($this->contentConstants) > 1) {
                $constantArray = (new \ArrayObject($this->contentConstants))->getArrayCopy();
                unset($constantArray[$key]);

                $checkConstants = [];
                array_walk_recursive($constantArray, function($value) use (&$checkConstants) {
                    $checkConstants[] = $value;
                });

                foreach ($constants as $constant) {
                    if (in_array($constant, $checkConstants)) {
                        $this->addError("contentConstants", Yii::t('models_error', 'В некоторых структурах ' .
                            'есть повторяющиеся константы'));

                        break;
                    }
                }
            }
        }
    }

    public function afterValidate()
    {
        $this->content = [
            'groups' => $this->contentGroups,
            'constants' => $this->contentConstants
        ];

        parent::afterValidate();
    }

    public function getFieldsForStructures(): array
    {
        if ($this->isNewEntity) {
            return [$this];
        }

        $notCleanAttributes = ['contentGroups', 'contentConstants', 'groupNotMeets', 'groups', 'reports', 'constants', 'groupsCanSent'];
        $cloneModel = clone $this;

        $data = [];
        foreach ($cloneModel->attributes as $key => $value) {
            if (!in_array($key, $notCleanAttributes)) {
                $cloneModel->{$key} = null;
            }
        }

        for ($i = 1; $i <= count($cloneModel->contentGroups); $i++) {
            $data[] = $cloneModel;
        }

        return $data;
    }
}