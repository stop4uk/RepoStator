<?php

namespace app\modules\reports\widgets\duplicating;

use yii\helpers\Json;
use yii\base\Widget;

final class DuplicatingWidget extends Widget
{
    /**
     * ID блока для поиска
     * @var string
     */
    public string $blockID;
    /**
     * Номер колонки в которую должна вставиться кнопка тиражирования. Если, не указан, кнопка создастся в автодобавленной колонке
     * @var integer
     */
    public $columnForButton = 0;
    /**
     * Если, указано, будут скопированы только элементы, содержащие данные класс
     * @var string|null
     */
    public string|null $filterDuplicateElements = null;
    /**
     * Стилевые классы кнопки копирования
     * @var string|null
     */
    public ?string $buttonClass = null;

    public function run()
    {
        $view = $this->getView();
        DuplicatingAsset::register($view);

        $runScriptData = Json::encode([
            'blockID' => $this->blockID,
            'columnForButton' => $this->columnForButton,
            'filterDuplicateElements' => $this->filterDuplicateElements,
            'buttonClass' => $this->buttonClass
        ]);
        $view->registerJs("new window.duplicate($runScriptData)");
    }
}