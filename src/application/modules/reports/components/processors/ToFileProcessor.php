<?php

namespace app\modules\reports\components\processors;

use Yii;
use PhpOffice\PhpSpreadsheet\{
    IOFactory,
    Reader\IReader,
    Spreadsheet,
    Style\Alignment,
    Style\Border,
    Style\Color,
    Style\Fill,
    Worksheet\PageSetup,
    Worksheet\Worksheet,
    Writer\IWriter,
    Writer\Xlsx
};

use app\helpers\CommonHelper;
use app\modules\reports\{
    components\base\BaseProcessor,
    components\base\BaseProcessorInterface,
    entities\ReportFormJobEntity,
    helpers\TemplateHelper
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\components\processors
 */
class ToFileProcessor extends BaseProcessor implements BaseProcessorInterface
{
    /**
     * @var IReader|Spreadsheet Документ
     */
    private $spreadsheet;
    /**
     * @var Worksheet Лист документа
     */
    private $sheet;
    /**
     * @var IWriter|Xlsx Коласс записи изменений
     */
    private $writer;
    /**
     * @var string|null Рашсширение файла и, через ucfirst($this->extention) - тип генератора для открытия шаблона
     */
    private $extension;

    /**
     * @vqr string|null
     */
    private $jobID;

    /**
     * @var array Индикаторы констант и правил, которые используются в загруженном шаблоне
     */
    private array $indicatorsForReplace = [];

    public function setJobID(string $jobID): void
    {
        $this->jobID = $jobID;
    }

    public function run(): ?bool
    {
        $process = $this->getSpreadsheet();

        if ( $this->template->table_template ) {
            $process
                ->getIndicatorsFromFile()
                ->getDataFromDB()
                ->calculateCountersForFile()
                ->replaceDataInSpreadsheet();
        } else {
            $process
                ->getIndicatorsAndGroupsFromTemplate()
                ->getDataFromDB()
                ->calculateAllCounters()
                ->setColumnsTitleAndHeader()
                ->setDataInRows()
                ->setPageSettings();
        }

        return $process
            ->write()
            ->save();
    }

    private function getSpreadsheet(): static
    {
        if ( $this->template->table_template ) {
            $file = Yii::getAlias($this->template->table_template);

            $this->extension = pathinfo($file, PATHINFO_EXTENSION);
            $this->spreadsheet = (IOFactory::createReader(ucfirst($this->extension)))->load($file);
        } else {
            $this->spreadsheet = new Spreadsheet();
        }

        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->extension = 'xlsx';
        return $this;
    }

    private function setColumnsTitleAndHeader(): static
    {
        $indicatorsNames = [];
        foreach ($this->indicatorsContent as $record => $data) {
            $indicatorsNames[$record] = $data['name'];
        }

        $columns = [];
        switch ($this->template->table_type) {
            case $this->template::REPORT_TABLE_TYPE_CONST:
                $columns = $this->periodDays ?: $indicatorsNames;
                $rowHeader = Yii::t('views', 'Группы');
                break;
            case $this->template::REPORT_TABLE_TYPE_GROUP:
                $columns = $this->groups;
                $rowHeader = Yii::t('views', $this->periodDays ? 'Дни' : 'Константы');
                break;
        }

        $firstRowForTable = 4;
        $secondRowForTable = 5;
        $this->setColumns(
            columns: $columns,
            firstRow: $firstRowForTable,
            secondRow: $secondRowForTable,
            groupsInHead: ($this->template->table_type == $this->template::REPORT_TABLE_TYPE_GROUP)
        );

        $this->sheet->setCellValue('A1', $this->template->name)->mergeCells("A1:{$this->sheet->getHighestColumn()}1");
        $this->setStyle(
            coords: [1, 1],
            alignHorizontal: Alignment::HORIZONTAL_CENTER,
            alignVertical: Alignment::VERTICAL_CENTER
        );

        if ( $this->template->form_datetime == $this->template::REPORT_DATETIME_PERIOD ) {
            $this->sheet->setCellValue('A2', $this->form->period . ($this->template->use_appg ? Yii::t('views', ' с АППГ') : ''))->mergeCells("A2:{$this->sheet->getHighestColumn()}2");
            $this->setStyle(
                coords: [1, 2],
                alignHorizontal: Alignment::HORIZONTAL_CENTER,
                alignVertical: Alignment::VERTICAL_CENTER
            );
        }

        if ( $this->periodDays ) {
            $this->sheet->setCellValue(
                coordinate: "A3",
                value: Yii::t('views', 'Период расчета: {periodDesc}. Правила расчета: {rules}', [
                    'rules' => implode(', ', $indicatorsNames),
                    'periodDesc' => TemplateHelper::getDatetimeType($this->template->form_datetime)
                ])
            )->mergeCells("A3:{$this->sheet->getHighestColumn()}3");
            $this->setStyle(
                coords: [1, 3],
                color: '34ADEB',
                alignHorizontal: Alignment::HORIZONTAL_CENTER,
                alignVertical: Alignment::VERTICAL_CENTER
            );
        }

        $selectRow = $this->template->use_appg
            ? $secondRowForTable
            : $firstRowForTable;

        $this->sheet->setCellValue("A$firstRowForTable", $rowHeader)->mergeCells("A$firstRowForTable:A$selectRow");
        $this->setStyle(
            coords: [1, $firstRowForTable],
            alignHorizontal: Alignment::HORIZONTAL_CENTER,
            alignVertical: Alignment::VERTICAL_CENTER
        );

        $this->sheet
            ->getStyle("A$firstRowForTable:" . $this->sheet->getHighestColumn() . $this->sheet->getHighestRow())
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000'));

        return $this;
    }

    private function setDataInRows(): static
    {
        $rowID = $rowStart = ($this->sheet->getHighestRow()+1);

        if ( $this->template->table_type == $this->template::REPORT_TABLE_TYPE_CONST ) {
            $arrayForForeach = $this->periodDays ?: $this->indicatorsContent;

            if ( $this->template->use_grouptype ) {
                foreach ($this->groupsTypeContent as $groupTypeID => $groupTypeData ) {
                    if ( $groupTypeData['groups'] ) {
                        foreach ($groupTypeData['groups'] as $groupID => $groupName) {
                            $columnID = 1;
                            $this->sheet->setCellValue([$columnID, $rowID], $groupName);

                            $this->setRowsByColumns(
                                column: $columnID,
                                row: $rowID,
                                group: $groupID,
                                data: $arrayForForeach,
                                attribute: 'calculateCounters'
                            );
                            $rowID++;
                        }

                        $columnID = 1;
                        $this->sheet->setCellValue([$columnID, $rowID], $groupTypeData['name']);
                        $this->setStyle(
                            coords: [$columnID, $rowID],
                            color: 'FFFF00',
                            alignHorizontal: Alignment::HORIZONTAL_RIGHT
                        );

                        $this->setRowsByColumns(
                            column: $columnID,
                            row: $rowID,
                            group: $groupTypeID,
                            data: $arrayForForeach,
                            attribute: 'calculateCountersByGroupType',
                            color: 'FFFF00'
                        );

                        $rowID++;
                    }
                }
            } else {
                foreach ($this->groups as $groupID => $groupName) {
                    $columnID = 1;
                    $this->sheet->setCellValue([$columnID, $rowID], $groupName);
                    $this->setRowsByColumns(
                        column: $columnID,
                        row: $rowID,
                        group: $groupID,
                        data: $arrayForForeach,
                        attribute: 'calculateCounters'
                    );

                    $rowID++;
                }
            }

            $columnID = 1;
            $this->sheet->setCellValue([$columnID, $rowID], Yii::t('views', 'ИТОГО'));
            $this->setStyle(
                coords: [$columnID, $rowID],
                color: '34ADEB',
                alignHorizontal: Alignment::HORIZONTAL_RIGHT
            );

            $this->setRowsByColumns(
                column: $columnID,
                row: $rowID,
                group: 0,
                data: $arrayForForeach,
                attribute: 'calculateCountersAll',
                color: '34ADEB'
            );

            foreach ($this->sheet->getColumnIterator('A', $this->sheet->getHighestDataColumn()) as $column) {
                $this->sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        } else {
            $indicatorsNames = [];
            foreach ($this->indicatorsContent as $record => $data) {
                $indicatorsNames[$record] = $data['name'];
            }

            $rowsData = $this->periodDays ?: $indicatorsNames;
            foreach ($rowsData as $key => $value ) {
                $columnID = 1;
                $this->sheet->setCellValue([$columnID, $rowID], is_array($value) ? $value['name'] : $value);

                foreach (array_keys($this->groups) as $group) {
                    $columnID++;
                    $this->sheet->setCellValue([$columnID, $rowID], ($this->calculateCounters['now'][$group][$key] ?? 0));

                    if ( $this->template->use_appg ) {
                        $columnID++;
                        $this->sheet->setCellValue([$columnID, $rowID], ($this->calculateCounters['appg'][$group][$key] ?? 0));
                    }
                }

                $rowID++;
            }

            $columnID = 1;
            $this->sheet->setCellValue([$columnID, $rowID], Yii::t('views', 'ИТОГО'));
            $this->setStyle(
                coords: [$columnID, $rowID],
                color: '34ADEB',
                alignHorizontal: Alignment::HORIZONTAL_RIGHT
            );

            foreach (array_keys($this->groups) as $group) {
                $columnID++;
                $this->sheet->setCellValue([$columnID, $rowID], ($this->calculateCountersAll['now'][$group] ?? 0));
                $this->setStyle(
                    coords: [$columnID, $rowID],
                    color: '34ADEB',
                );

                if ( $this->template->use_appg ) {
                    $columnID++;
                    $this->sheet->setCellValue([$columnID, $rowID], ($this->calculateCounters['appg'][$group] ?? 0));
                    $this->setStyle(
                        coords: [$columnID, $rowID],
                        color: '34ADEB',
                        alignHorizontal: Alignment::HORIZONTAL_CENTER
                    );
                }
            }

            foreach ($this->sheet->getColumnIterator('A', $this->sheet->getHighestDataColumn()) as $column) {
                $this->sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        $this->sheet
            ->getStyle("A$rowStart:" . $this->sheet->getHighestColumn() . $this->sheet->getHighestRow())
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000'));

        return $this;
    }

    private function getIndicatorsFromFile(): static
    {
        $useIndicators = [];
        $useIndicatorsWithInner = [];

        foreach ($this->sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);

            foreach ($cellIterator as $cell) {
                if ( !$cell->getValue() ) {
                    continue;
                }

                preg_match_all('/{{(.*?)}}/', $cell->getValue(), $indicators);
                if ( $indicators[1] ) {
                    foreach ($indicators[1] as $indicator) {
                        $constant = explode('#', $indicator);
                        $useIndicators[$constant[0]] = $constant[0];
                        $useIndicatorsWithInner[$constant[0]][] = $indicator;
                    }
                }
            }

            if ( $useIndicators ) {
                $this->setIndicators($useIndicators);
                foreach ($useIndicatorsWithInner as $record => $items) {
                    $this->indicatorsForReplace[$record] = array_unique($items);
                }
            }
        }

        return $this;
    }

    private function calculateCountersForFile(): static
    {
        if ( !isset($this->sentData['now']) ) {
            return $this;
        }

        $calculatedValue = [];
        foreach ($this->sentData['now'] as $groupID => $pCounters) {
            foreach ($pCounters as $period => $counters) {
                foreach ($counters as $key => $value) {
                    if ( in_array($key, $this->indicators) ) {
                        $calculatedValue[$groupID][$key]['all'] = ($calculatedValue[$groupID][$key]['all'] ?? 0) + $value;
                        if ( date('Y-m-d', $this->period['now']['end']) == date('Y-m-d', $period) ) {
                            $calculatedValue[$groupID][$key]['D'] = ($calculatedValue[$groupID][$key]['D'] ?? 0) + $value;
                        }

                        if ( date('Y-m', $this->period['now']['end']) == date('Y-m', $period) ) {
                            $calculatedValue[$groupID][$key]['M'] = ($calculatedValue[$groupID][$key]['M'] ?? 0) + $value;
                        }

                        if ( date('Y', $this->period['now']['end']) == date('Y', $period) ) {
                            $calculatedValue[$groupID][$key]['Y'] = ($calculatedValue[$groupID][$key]['Y'] ?? 0) + $value;
                        }
                    }
                }
            }
        }

        foreach ($calculatedValue as $groupID => $countersData) {
            foreach ($countersData as $recordName => $counters) {
                $this->calculateCounters[$recordName] = ($this->calculateCounters[$recordName] ?? 0) + $counters['all'];
                $this->calculateCounters[$recordName. '#D'] = ($this->calculateCounters[$recordName. '#D'] ?? 0) + ($counters['D'] ?? 0);
                $this->calculateCounters[$recordName. '#M'] = ($this->calculateCounters[$recordName. '#M'] ?? 0) + ($counters['M'] ?? 0);
                $this->calculateCounters[$recordName. '#Y'] = ($this->calculateCounters[$recordName. '#Y'] ?? 0) + ($counters['Y'] ?? 0);
            }

            foreach ($this->indicatorsRule as $ruleName) {
                $constants = $this->indicatorsContent[$ruleName]['constants'];
                $groups = $this->indicatorsContent[$ruleName]['groups_only']
                    ? CommonHelper::explodeField($this->indicatorsContent[$ruleName]['groups_only'])
                    : [];

                foreach ($countersData as $recordName => $counters) {
                    if ( in_array($recordName, $constants) ) {
                        if ( ($groups && in_array($groupID, $groups)) || !$groups ) {
                            $this->calculateCounters[$ruleName] = ($this->calculateCounters[$ruleName] ?? 0) + $counters['all'];
                            $this->calculateCounters[$ruleName. '#D'] = ($this->calculateCounters[$ruleName. '#D'] ?? 0) + ($counters['D'] ?? 0);
                            $this->calculateCounters[$ruleName. '#M'] = ($this->calculateCounters[$ruleName. '#M'] ?? 0) + ($counters['M'] ?? 0);
                            $this->calculateCounters[$ruleName. '#Y'] = ($this->calculateCounters[$ruleName. '#Y'] ?? 0) + ($counters['Y'] ?? 0);
                        }
                    }
                }
            }
        }

        return $this;
    }

    private function replaceDataInSpreadsheet(): void
    {
        foreach ($this->sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);

            foreach ($cellIterator as $cell) {
                if ( !$cell->getValue() ) {
                    continue;
                }

                if (str_contains($cell->getValue(), '#period#')) {
                    $cell->setValue(str_replace('#period#', $this->form->period, $cell->getValue()));
                } else {
                    $elementsToReplace = $this->calculateCounters;

                    $cell->setValue(preg_replace_callback('/{{(.*?)}}/', function($matches) use ($elementsToReplace) {
                        return $elementsToReplace[$matches[1]] ?? 0;
                    }, $cell->getValue()));
                }
            }
        }
    }

    private function setPageSettings(): void
    {
        $this->sheet
            ->getPageSetup()
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToPage(1);
    }

    private function write(): static
    {
        $this->writer = match( (bool)$this->template->table_template ) {
            true => IOFactory::createWriter($this->spreadsheet, ucfirst($this->extension)),
            false => new Xlsx($this->spreadsheet)
        };

        return $this;
    }

    private function save()
    {
        $fileName = urlencode(Yii::$app->security->generateRandomString(6)) . '.' . $this->extension;

        if ( $this->template->form_usejobs ) {
            $this->writer->save(Yii::getAlias(Yii::$app->params['downloadFormFilesAlias']) . DIRECTORY_SEPARATOR . $fileName);

            (ReportFormJobEntity::find()
                ->with(['user', 'template'])
                ->where(['job_id' => $this->jobID])
                ->limit(1)
                ->one())->setComplete(
                    file: $fileName,
                    formPeriod: $this->form->period
                );

            return true;
        }

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Cache-Control: max-age=0");
        $this->writer->save("php://output");
        die;
    }

    private function setStyle(
        array $coords,
        ?string $color = null,
        ?string $alignHorizontal = null,
        ?string $alignVertical = null
    ): void
    {
        if ( $color ) {
            $this->sheet
                ->getStyle($coords)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($color);
        }

        if ( $alignHorizontal || $alignVertical ) {
            $sheet = $this->sheet
                ->getStyle($coords)
                ->getAlignment();

            if ( $alignHorizontal ) {
                $sheet->setHorizontal($alignHorizontal);
            }

            if ( $alignVertical ) {
                $sheet->setVertical($alignVertical);
            }
        }
    }

    private function setColumns(
        array $columns,
        int $firstRow,
        int $secondRow,
        bool $groupsInHead = false
    ): void
    {
        $columnID = 2;
        foreach($columns as $columnName) {
            if ( $this->template->use_appg ) {
                $mergeColumnID = ($columnID+1);
                $this->sheet->setCellValue([$columnID, $firstRow], $columnName)
                    ->mergeCells([$columnID, $firstRow, $mergeColumnID, $firstRow]);

                $this->sheet->setCellValue([$columnID, $secondRow], date('Y', $this->period['now']['end']));
                $this->sheet->setCellValue([$mergeColumnID, $secondRow], date('Y', $this->period['appg']['end']));

                $this->setStyle(
                    coords: [$columnID, $secondRow],
                    alignHorizontal: Alignment::HORIZONTAL_CENTER,
                    alignVertical: Alignment::VERTICAL_CENTER
                );

                $this->setStyle(
                    coords: [$mergeColumnID, $secondRow],
                    alignHorizontal: Alignment::HORIZONTAL_CENTER,
                    alignVertical: Alignment::VERTICAL_CENTER
                );
            } else {
                $this->sheet->setCellValue([$columnID, $firstRow], $columnName);
                $this->sheet->getStyle([$columnID, $firstRow])->getAlignment()->setTextRotation(90)->setWrapText(true);
                $this->sheet->getRowDimension($firstRow)->setRowHeight($groupsInHead ? 220 : 74);
            }

            $this->setStyle(
                coords: [$columnID, $firstRow],
                alignHorizontal: Alignment::HORIZONTAL_CENTER,
                alignVertical:  $this->template->use_appg ? Alignment::VERTICAL_CENTER : null
            );

            $column = $this->sheet->getColumnDimensionByColumn($columnID);
            if ( $this->template->use_appg ) {
                $column->setAutoSize(true);
            } else {
                $column->setWidth(4);
            }

            $columnID += ($this->template->use_appg ? 2 : 1 );
        }
    }

    private function setRowsByColumns(
        int $column,
        int $row,
        int $group,
        array $data,
        string $attribute,
        ?string $color = null
    ): void
    {
        foreach (array_keys($data) as $key) {
            $column++;
            $this->sheet->setCellValue([$column, $row], ($this->$attribute['now'][$group][$key] ?? 0));
            $this->setStyle(
                coords: [$column, $row],
                color: $color,
                alignHorizontal: Alignment::HORIZONTAL_CENTER
            );

            if ( $this->template->use_appg ) {
                $column++;
                $this->sheet->setCellValue([$column, $row], ($this->$attribute['appg'][$group][$key] ?? 0));
                $this->setStyle(
                    coords:[$column, $row],
                    color: $color,
                    alignHorizontal: Alignment::HORIZONTAL_CENTER
                );
            }
        }
    }
}
