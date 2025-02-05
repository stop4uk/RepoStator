<?php

namespace app\modules\reports\components\formReport\processors;

use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\{
    Spreadsheet,
    Style\Alignment,
    Style\Border,
    Style\Color,
    Style\Fill,
    Worksheet\PageSetup,
    Writer\Xlsx
};

use app\helpers\CommonHelper;
use app\modules\reports\{
    components\formReport\base\BaseProcessor,
    helpers\TemplateHelper
};
use app\modules\users\{
    repositories\GroupRepository,
    repositories\GroupTypeRepository
};

final class FromDynamic extends BaseProcessor
{
    public function form(): void
    {
        $this->setSpreadsheet()
            ->getIndicatorsAndGroupsFromTemplate()
            ->getDataFromDB()
            ->calculateAllCounters()
            ->setColumnsTitleAndHeader()
            ->setDataInRows();

        $this->setPageSettings(PageSetup::PAPERSIZE_A4, PageSetup::ORIENTATION_LANDSCAPE, 1);
        $this->write();
    }
    private function setSpreadsheet(): FromDynamic
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        return $this;
    }

    final public function getIndicatorsAndGroupsFromTemplate(): FromDynamic
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

    final public function calculateAllCounters(): FromDynamic
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

    private function setColumnsTitleAndHeader(): FromDynamic
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

        if ($this->template->form_datetime == $this->template::REPORT_DATETIME_PERIOD) {
            $this->sheet->setCellValue('A2', $this->form->period . ($this->template->use_appg ? Yii::t('views', ' с АППГ') : ''))->mergeCells("A2:{$this->sheet->getHighestColumn()}2");
            $this->setStyle(
                coords: [1, 2],
                alignHorizontal: Alignment::HORIZONTAL_CENTER,
                alignVertical: Alignment::VERTICAL_CENTER
            );
        }

        if ($this->periodDays) {
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

    private function setDataInRows(): FromDynamic
    {
        $rowID = $rowStart = ($this->sheet->getHighestRow()+1);

        if ($this->template->table_type == $this->template::REPORT_TABLE_TYPE_CONST) {
            $arrayForForeach = $this->periodDays ?: $this->indicatorsContent;

            if ($this->template->use_grouptype) {
                foreach ($this->groupsTypeContent as $groupTypeID => $groupTypeData) {
                    if ($groupTypeData['groups']) {
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
            foreach ($rowsData as $key => $value) {
                $columnID = 1;
                $this->sheet->setCellValue([$columnID, $rowID], is_array($value) ? $value['name'] : $value);

                foreach (array_keys($this->groups) as $group) {
                    $columnID++;
                    $this->sheet->setCellValue([$columnID, $rowID], ($this->calculateCounters['now'][$group][$key] ?? 0));

                    if ($this->template->use_appg) {
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

                if ($this->template->use_appg) {
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

    private function write(): void
    {
        $this->writer = new Xlsx($this->spreadsheet);
    }

    private function setStyle(
        array $coords,
        ?string $color = null,
        ?string $alignHorizontal = null,
        ?string $alignVertical = null
    ): void
    {
        if ($color) {
            $this->sheet
                ->getStyle($coords)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB($color);
        }

        if ($alignHorizontal || $alignVertical) {
            $sheet = $this->sheet
                ->getStyle($coords)
                ->getAlignment();

            if ($alignHorizontal) {
                $sheet->setHorizontal($alignHorizontal);
            }

            if ($alignVertical) {
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
            if ($this->template->use_appg) {
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
            if ($this->template->use_appg) {
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

            if ($this->template->use_appg) {
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