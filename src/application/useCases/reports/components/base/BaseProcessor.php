<?php

namespace app\useCases\reports\components\base;

use Yii;
use yii\base\Component;
use yii\helpers\{
    ArrayHelper,
    Json
};

use app\components\base\BaseAR;
use app\helpers\CommonHelper;
use app\useCases\reports\{
    entities\ReportDataEntity,
    entities\ReportFormTemplateEntity,
    repositories\ConstantBaseRepository,
    repositories\ConstantruleBaseRepository,
    forms\StatisticForm,
};

use app\useCases\users\{
    repositories\group\GroupRepository,
    repositories\group\GroupTypeRepository
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\base
 */
class BaseProcessor extends Component
{
    protected readonly StatisticForm $form;
    protected readonly ReportFormTemplateEntity $template;
    protected readonly array $period;
    protected readonly array $periodDays;

    public array $indicators = [];
    public array $indicatorsContent = [];
    public array $indicatorsRule = [];
    public array $groups = [];
    public array $groupsTypeContent = [];
    public array $groupsToType = [];

    public array $sentData = [];
    public array $calculateCounters = [];
    public array $calculateCountersByGroupType = [];
    public array $calculateCountersAll = [];

    public function __construct($form, $template)
    {
        $this->form = $form;
        $this->template = $template;
        $this->period = $this->getPeriod();
        $this->periodDays = $this->getPeriodDays();
    }

    private function getPeriod(): array
    {
        $explodeDates = explode(' - ', $this->form->period);
        $startTime = match ($this->template->form_datetime) {
            $this->template::REPORT_DATETIME_PERIOD => strtotime($explodeDates[0] . ' 00:00:00'),
            $this->template::REPORT_DATETIME_MONTH => strtotime(date('Y-m-01 00:00:00', strtotime($explodeDates[1]))),
            $this->template::REPORT_DATETIME_WEEK => strtotime(($explodeDates[1] . '-' . (date('N', strtotime($explodeDates[1]))-1) . ' days') . ' 00:00:00')
        };

        $dates = [
            'now' => [
                'start' => $startTime,
                'end' => strtotime($explodeDates[1].' 23:59:59')
            ],
            'appg' => []
        ];

        if ($this->template->use_appg) {
            $dates['appg'] = [
                'start' => ($dates['now']['start'] - 31536000),
                'end' => ($dates['now']['end'] - 31536000)
            ];
        }

        return $dates;
    }

    private function getPeriodDays(): array
    {
        $periodDates = [];
        $startDate = $this->period['now']['start'];
        $endDate = $this->period['now']['end'];

        if ( $this->template->form_datetime != $this->template::REPORT_DATETIME_PERIOD ) {
            while ($startDate <= $endDate) {
                $periodDates[$startDate] = Yii::$app->formatter->asDate($startDate);
                $startDate = strtotime('+1 day', $startDate);
            }
        }

        return $periodDates;
    }

    public function getIndicatorsAndGroupsFromTemplate(): static
    {
        $inversionFind = ['table_columns' => 'table_rows', 'table_rows' => 'table_columns'];
        $findColumn = match ($this->template->table_type) {
            $this->template::REPORT_TABLE_TYPE_GROUP => 'table_rows',
            $this->template::REPORT_TABLE_TYPE_CONST => 'table_columns'
        };

        $groups = GroupRepository::getAll([]);
        $groupsFromTemplate = CommonHelper::explodeField(
            string: $this->template->{$inversionFind[$findColumn]}
        );

        $this->groups = ArrayHelper::map($groups, 'id', 'name');
        foreach ($this->groups as $groupID => $groupName) {
            if (!in_array($groupID, $groupsFromTemplate)) {
                unset($this->groups[$groupID]);
            }
        }

        if ($this->template->use_grouptype) {
            $types = GroupTypeRepository::getAll([], true);
            $groupsWithType = ArrayHelper::map($groups, 'id', 'name', 'type_id');
            $this->groupsToType = ArrayHelper::map($groups, 'id', 'type_id');

            foreach ($types as $typeID => $typeName) {
                if (isset($groupsWithType[$typeID])) {
                    $innerGroups = [];

                    foreach ($groupsWithType[$typeID] as $id => $name) {
                        if (in_array($id, $groupsFromTemplate)) {
                            $innerGroups[$id] = $name;
                        }
                    }

                    $this->groupsTypeContent[$typeID] = [
                        'name' => $typeName,
                        'groups' => $innerGroups
                    ];
                }
            }

            foreach ($this->groupsTypeContent as $groupID => $inner) {
                $groupList = $inner['groups'];

                uksort($groupList, function($a, $b) use ($groupsFromTemplate) {
                    return array_search($a, $groupsFromTemplate) - array_search($b, $groupsFromTemplate);
                });

                $this->groupsTypeContent[$groupID]['groups'] = $groupList;
            }
        }

        $indicatorsFromTemplate = CommonHelper::explodeField(
            string: $this->template->{$findColumn}
        );

        $this->setIndicators($indicatorsFromTemplate);
        uksort($this->indicatorsContent, function($a, $b) use ($indicatorsFromTemplate) {
            return array_search($a, $indicatorsFromTemplate) - array_search($b, $indicatorsFromTemplate);
        });

        return $this;
    }

    public function getDataFromDB(): static
    {
        $data = ['now' => [], 'appg' => []];

        $data['now'] = ReportDataEntity::find()
            ->where(['record_status' => BaseAR::RSTATUS_ACTIVE])
            ->andFilterWhere(['>=', 'report_datetime', $this->period['now']['start']])
            ->andFilterWhere(['<=', 'report_datetime', $this->period['now']['end']])
            ->all();

        if ($this->period['appg']) {
            $data['appg'] = ReportDataEntity::find()
                ->where(['record_status' => BaseAR::RSTATUS_ACTIVE])
                ->andFilterWhere(['>=', 'report_datetime', $this->period['appg']['start']])
                ->andFilterWhere(['<=', 'report_datetime', $this->period['appg']['end']])
                ->all();
        }

        if ($this->indicators) {
            foreach ($data as $type => $rows) {
                if ($rows) {
                    foreach ($rows as $sentData) {
                        $counters = Json::decode($sentData->content);
                        $clean = true;

                        foreach ($counters as $indicator => $value) {
                            if (in_array($indicator, $this->indicators)) {
                                $clean = false;
                                break;
                            }
                        }

                        if (!$clean) {
                            $this->sentData[$type][$sentData->group_id][$sentData->report_datetime] = $counters;
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function setIndicators(array $indicatorsArray): void
    {
        $constants = (ConstantBaseRepository::getAllBy(['record' => $indicatorsArray], []))->all();
        $rules = (ConstantruleBaseRepository::getAllBy(['record' => $indicatorsArray], []))->all();

        $this->indicators = ArrayHelper::map($constants, 'id', 'record');
        foreach ($constants as $constant) {
            $this->indicatorsContent[$constant->record] = [
                'name' => $constant->name,
                'rule' => $constant->record,
                'constants' => [$constant->record]
            ];
        }

        if ($rules) {
            foreach ($rules as $rule) {
                preg_match_all('/\"(.*?)\"/', $rule->rule, $innerConstants);

                if ($innerConstants[1]) {
                    $this->indicators = ArrayHelper::merge($this->indicators, $innerConstants[1]);
                    $this->indicatorsRule[] = $rule->record;
                    $this->indicatorsContent[$rule->record] = [
                        'name' => $rule->name,
                        'rule' => $rule->rule,
                        'groups_only' => $rule->groups_only,
                        'constants' => $innerConstants[1]
                    ];

                }
            }
        }
    }

    public function calculateAllCounters(): static
    {
        foreach ($this->period as $type => $periods) {
            if (
                !$periods
                || !isset($this->sentData[$type])
            ) {
                continue;
            }

            foreach ($this->groups as $groupID => $name) {
                if (!isset($this->sentData[$type][$groupID])) {
                    continue;
                }

                switch ($this->template->form_datetime) {
                    case $this->template::REPORT_DATETIME_PERIOD:
                        $this->calculateForPeriod(
                            type: $type,
                            group: $groupID,
                            indicators: $this->indicatorsContent
                        );
                        break;
                    default:
                        $this->calculateForDays(
                            type: $type,
                            group: $groupID,
                            indicators: $this->indicatorsContent,
                        );
                        break;
                }
            }
        }

        return $this;
    }

    private function calculateForDays(
        string $type,
        int $group,
        array $indicators
    ): void {
        foreach (array_keys($this->periodDays) as $day) {
            $daySumm = [];

            foreach ($indicators as $indicatorData) {
                $calculateValues = [];

                foreach ($this->sentData[$type][$group] as $period => $counters) {
                    foreach ($counters as $keyCounter => $valueCounter) {
                        if (
                            date('Y-m-d', $day) == date('Y-m-d', $period)
                            && in_array($keyCounter, $indicatorData['constants'])
                        ) {
                            $calculateValues[$keyCounter] = ($calculateValues[$keyCounter] ?? 0) + $valueCounter;
                        }
                    }
                }

                if (count($indicatorData['constants']) == 1) {
                    $daySumm[] = array_sum($calculateValues);
                } else {
                    $daySumm[] = $this->runEval(
                        $this->replaceConstantToValues(
                            rule: $indicatorData['rule'],
                            indicators: $calculateValues
                        )
                    );
                }
            }

            $this->calculateCounters[$type][$group][$day] = ($this->calculateCounters[$type][$group][$day] ?? 0) + array_sum($daySumm);

            if ($this->template->table_type == $this->template::REPORT_TABLE_TYPE_GROUP) {
                $this->calculateCountersAll[$type][$group] = ($this->calculateCountersAll[$type][$group] ?? 0) + array_sum($daySumm);
            } else {
                $this->calculateCountersAll[$type][0][$day] = ($this->calculateCountersAll[$type][0][$day] ?? 0) + array_sum($daySumm);
            }

            if ($this->template->use_grouptype) {
                $this->calculateCountersByGroupType[$type][$this->groupsToType[$group]][$day] = ($this->calculateCountersByGroupType[$type][$this->groupsToType[$group]][$day] ?? 0) + array_sum($daySumm);
            }
        }
    }

    private function calculateForPeriod(
        string $type,
        int $group,
        array $indicators
    ): void {
        foreach ($indicators as $record => $indicatorData) {
            $calculateValues = [];
            foreach ($this->sentData[$type][$group] as $counters) {
                foreach ($counters as $keyCounter => $valueCounter) {
                    if (in_array($keyCounter, $indicatorData['constants'])) {
                        $calculateValues[$keyCounter] = ($calculateValues[$keyCounter] ?? 0) + $valueCounter;
                    }
                }
            }

            if (count($indicatorData['constants']) == 1) {
                $resultValue = array_sum($calculateValues);
            } else {
                $resultValue = $this->runEval(
                    $this->replaceConstantToValues(
                        rule: $indicatorData['rule'],
                        indicators: $calculateValues
                    )
                );
            }

            $this->calculateCounters[$type][$group][$record] = ($this->calculateCounters[$type][$group][$record] ?? 0) + $resultValue;

            if ($this->template->table_type == $this->template::REPORT_TABLE_TYPE_GROUP) {
                $this->calculateCountersAll[$type][$group] = ($this->calculateCountersAll[$type][$group] ?? 0) + $resultValue;
            } else {
                $this->calculateCountersAll[$type][0][$record] = ($this->calculateCountersAll[$type][0][$record] ?? 0) + $resultValue;
            }

            if ($this->template->use_grouptype) {
                $this->calculateCountersByGroupType[$type][$this->groupsToType[$group]][$record] = ($this->calculateCountersByGroupType[$type][$this->groupsToType[$group]][$record] ?? 0) + $resultValue;
            }
        }
    }

    private function replaceConstantToValues(
        string $rule,
        array $indicators
    ): string {
        $changeRule = $rule;

        preg_match_all('/\"(.*?)\"/', $changeRule, $constants);
        foreach ($indicators as $record => $value) {
            if (in_array($record, $constants[1])) {
                $changeRule = str_replace(
                    search: '"' . $record . '"',
                    replace: ($value ?? 0),
                    subject: $changeRule
                );
            }
        }

        foreach ($constants[1] as $constant) {
            if (!in_array($constant, array_keys($indicators))) {
                $changeRule = str_replace(
                    search: '"' . $constant . '"',
                    replace: 0,
                    subject: $changeRule
                );
            }
        }

        return $changeRule;
    }

    private function runEval(string $rule): int
    {
        if (preg_match('/^[0-9\+\-\*\/\(\)\s]+$/', $rule)) {
            return eval("return $rule;");
        }

        return 0;
    }
}
