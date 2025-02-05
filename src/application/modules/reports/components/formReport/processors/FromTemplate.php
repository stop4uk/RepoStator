<?php

namespace app\modules\reports\components\formReport\processors;

use app\components\attachedFiles\AttachFileEntity;
use app\helpers\CommonHelper;
use app\modules\reports\components\formReport\base\BaseProcessor;
use app\modules\reports\entities\ReportFormJobEntity;
use app\modules\reports\entities\ReportFormTemplateEntity;
use app\modules\reports\forms\StatisticForm;
use app\modules\reports\helpers\TemplateHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class FromTemplate extends BaseProcessor
{
    private array $file = [];
    private array $indicatorsForReplace = [];

    public function form(): ?bool
    {
        return $this
            ->setSpreadsheet()
            ->getIndicatorsFromFile()
            ->getDataFromDB()
            ->calculateCountersForFile()
            ->replaceDataInSpreadsheet()
            ->setPageSettings(
                paperSize: PageSetup::PAPERSIZE_A4,
                orientation: PageSetup::ORIENTATION_LANDSCAPE,
                fitToPage: 1
            );
    }

    private function setSpreadsheet(): FromTemplate
    {
        $file = array_shift($this->template->getAttachedFiles(false));
        $reader = IOFactory::createReader(ucfirst($this->file->file_extension));

        $this->spreadsheet = ;
        $this->sheet = $this->spreadsheet->getActiveSheet();

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
                if (!$cell->getValue()) {
                    continue;
                }

                preg_match_all('/{{(.*?)}}/', $cell->getValue(), $indicators);
                if ($indicators[1]) {
                    foreach ($indicators[1] as $indicator) {
                        $constant = explode('#', $indicator);
                        $useIndicators[$constant[0]] = $constant[0];
                        $useIndicatorsWithInner[$constant[0]][] = $indicator;
                    }
                }
            }

            if ($useIndicators) {
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
        if (!isset($this->sentData['now'])) {
            return $this;
        }

        $calculatedValue = [];
        foreach ($this->sentData['now'] as $groupID => $pCounters) {
            foreach ($pCounters as $period => $counters) {
                foreach ($counters as $key => $value) {
                    if (in_array($key, $this->indicators)) {
                        $calculatedValue[$groupID][$key]['all'] = ($calculatedValue[$groupID][$key]['all'] ?? 0) + $value;
                        if (date('Y-m-d', $this->period['now']['end']) == date('Y-m-d', $period)) {
                            $calculatedValue[$groupID][$key]['D'] = ($calculatedValue[$groupID][$key]['D'] ?? 0) + $value;
                        }

                        if (date('Y-m', $this->period['now']['end']) == date('Y-m', $period)) {
                            $calculatedValue[$groupID][$key]['M'] = ($calculatedValue[$groupID][$key]['M'] ?? 0) + $value;
                        }

                        if (date('Y', $this->period['now']['end']) == date('Y', $period)) {
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
                    if (in_array($recordName, $constants)) {
                        if (($groups && in_array($groupID, $groups)) || !$groups) {
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
                if (!$cell->getValue()) {
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

    private function write(): static
    {
        $this->writer = match((bool)$this->template->table_template) {
            true => IOFactory::createWriter($this->spreadsheet, ucfirst($this->extension)),
            false => new Xlsx($this->spreadsheet)
        };

        return $this;
    }

    private function save()
    {
        $fileName = urlencode(Yii::$app->security->generateRandomString(6)) . '.' . $this->extension;

        if ($this->template->form_usejobs) {
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