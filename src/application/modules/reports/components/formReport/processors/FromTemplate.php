<?php

namespace app\modules\reports\components\formReport\processors;

use Yii;
use PhpOffice\PhpSpreadsheet\{
    IOFactory,
    Worksheet\PageSetup
};

use app\helpers\CommonHelper;
use app\modules\reports\components\formReport\base\BaseProcessor;

final class FromTemplate extends BaseProcessor
{
    public $templateRecord = [];
    private array $indicatorsForReplace = [];

    public function form(): void
    {
        $this->setSpreadsheet()
            ->getIndicatorsFromFile()
            ->getDataFromDB()
            ->calculateCountersForFile()
            ->replaceDataInSpreadsheet();

        $this->setPageSettings(PageSetup::PAPERSIZE_A4, PageSetup::ORIENTATION_LANDSCAPE, 1);
        $this->write();
    }

    private function setSpreadsheet(): FromTemplate
    {
        $this->templateRecord = $this->template->getAttachedFiles(false)[0];
        $template = $this->template->getAttachFile($this->templateRecord['file_hash']);
        $reader = IOFactory::createReader(ucfirst($this->templateRecord['file_extension']));

        $fileName = Yii::$app->getSecurity()->generateRandomString(6);
        $fileExtension = $this->templateRecord['file_extension'];
        $filePath = Yii::getAlias('@runtime/'.env('YII_FILES_TEMPORARY_PATH', 'tmpFiles')) . DIRECTORY_SEPARATOR . implode('.', [$fileName, $fileExtension]);

        $tempFile = fopen( $filePath, 'wb');
        fwrite($tempFile, $template['content']);
        fclose($tempFile);

        $this->spreadsheet = $reader->load($filePath);
        $this->sheet = $this->spreadsheet->getActiveSheet();

        return $this;
    }

    private function getIndicatorsFromFile(): FromTemplate
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

    private function calculateCountersForFile(): FromTemplate
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

    private function write(): void
    {
        $this->writer = IOFactory::createWriter($this->spreadsheet, ucfirst($this->templateRecord['file_extension']));
    }
}