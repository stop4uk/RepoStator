<?php

namespace app\modules\reports\components\formReport\base;

use Yii;
use yii\base\Component;
use yii\helpers\{
    ArrayHelper,
    Json
};
use PhpOffice\PhpSpreadsheet\{
    Reader\IReader,
    Spreadsheet,
    Worksheet\Worksheet,
    Writer\IWriter,
    Writer\Xlsx
};

use app\components\base\BaseAR;
use app\modules\reports\{
    components\formReport\dto\StatFormDTO,
    components\formReport\dto\TemplateDTO,
    entities\ReportDataEntity,
    entities\ReportFormTemplateEntity,
    repositories\ConstantRepository,
    repositories\ConstantruleRepository
};


/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\base
 */
abstract class BaseProcessor extends Component
{
    /**
     * @var IReader|Spreadsheet Документ
     */
    public $spreadsheet;
    /**
     * @var Worksheet Лист документа
     */
    public $sheet;
    /**
     * @var IWriter|Xlsx Коласс записи изменений
     */
    public $writer;

    public readonly StatFormDTO $form;
    protected readonly TemplateDTO $template;
    protected readonly array $period;
    protected readonly array $periodDays;

    protected array $indicators = [];
    protected array $indicatorsContent = [];
    protected array $indicatorsRule = [];
    protected array $groups = [];
    protected array $groupsTypeContent = [];
    protected array $groupsToType = [];

    protected array $sentData = [];
    protected array $calculateCounters = [];
    protected array $calculateCountersByGroupType = [];
    protected array $calculateCountersAll = [];
    protected string $jobID = '';

    public function __construct(
        StatFormDTO $form,
        TemplateDTO $template
    ) {
        $this->form = $form;
        $this->template = $template;

        $this->period = $this->getPeriod();
        $this->periodDays = $this->getPeriodDays();
    }

    abstract public function form();

    final public function setJobID(string $id): void
    {
        $this->jobID = $id;
    }

    final public function getDataFromDB(): static
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
                        $counters = $sentData->content;
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

    final public function setIndicators(array|string $indicatorsArray): void
    {
        $constants = (ConstantRepository::getAllBy(['record' => $indicatorsArray], []))->all();
        $rules = (ConstantruleRepository::getAllBy(['record' => $indicatorsArray], []))->all();

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

    final public function setPageSettings(
        int $paperSize,
        string $orientation,
        int|null $fitToPage = null
    ): void {
        $this->sheet
            ->getPageSetup()
            ->setPaperSize($paperSize)
            ->setOrientation($orientation);

        if ($fitToPage) {
            $this->sheet->getPageSetup()->setFitToPage($fitToPage);
        }
    }

    protected function calculateForDays(
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

            if ($this->template->table_type == ReportFormTemplateEntity::REPORT_TABLE_TYPE_GROUP) {
                $this->calculateCountersAll[$type][$group] = ($this->calculateCountersAll[$type][$group] ?? 0) + array_sum($daySumm);
            } else {
                $this->calculateCountersAll[$type][0][$day] = ($this->calculateCountersAll[$type][0][$day] ?? 0) + array_sum($daySumm);
            }

            if ($this->template->use_grouptype) {
                $this->calculateCountersByGroupType[$type][$this->groupsToType[$group]][$day] = ($this->calculateCountersByGroupType[$type][$this->groupsToType[$group]][$day] ?? 0) + array_sum($daySumm);
            }
        }
    }

    protected function calculateForPeriod(
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

            if ($this->template->table_type == ReportFormTemplateEntity::REPORT_TABLE_TYPE_GROUP) {
                $this->calculateCountersAll[$type][$group] = ($this->calculateCountersAll[$type][$group] ?? 0) + $resultValue;
            } else {
                $this->calculateCountersAll[$type][0][$record] = ($this->calculateCountersAll[$type][0][$record] ?? 0) + $resultValue;
            }

            if ($this->template->use_grouptype) {
                $this->calculateCountersByGroupType[$type][$this->groupsToType[$group]][$record] = ($this->calculateCountersByGroupType[$type][$this->groupsToType[$group]][$record] ?? 0) + $resultValue;
            }
        }
    }

    private function getPeriod(): array
    {
        $explodeDates = explode(' - ', $this->form->period);
        $startTime = match ($this->template->form_datetime) {
            ReportFormTemplateEntity::REPORT_DATETIME_PERIOD => strtotime($explodeDates[0] . ' 00:00:00'),
            ReportFormTemplateEntity::REPORT_DATETIME_MONTH => strtotime(date('Y-m-01 00:00:00', strtotime($explodeDates[1]))),
            ReportFormTemplateEntity::REPORT_DATETIME_WEEK => strtotime(($explodeDates[1] . '-' . (date('N', strtotime($explodeDates[1]))-1) . ' days') . ' 00:00:00')
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

        if ($this->template->form_datetime != ReportFormTemplateEntity::REPORT_DATETIME_PERIOD) {
            while ($startDate <= $endDate) {
                $periodDates[$startDate] = Yii::$app->formatter->asDate($startDate);
                $startDate = strtotime('+1 day', $startDate);
            }
        }

        return $periodDates;
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
        if (preg_match ('/^[0-9\+\-\*\/\(\)\s]+$/', $rule)) {
            return eval("return $rule;");
        }

        return 0;
    }
}
