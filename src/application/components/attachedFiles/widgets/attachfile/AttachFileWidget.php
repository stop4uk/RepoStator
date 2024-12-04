<?php

namespace app\components\attachedFiles\widgets\attachfile;

use yii\base\Widget;
use yii\db\ActiveRecordInterface;

final class AttachFileWidget extends Widget
{
    const MODE_MANY = 'files';
    const MODE_ONE = 'oneFile';

    /**
     * @var string
     */
    public $blockTitle;
    /**
     * @var string
     */
    public $uploadButtonTitle;
    /**
     * @var bool
     */
    public $canDeleted = true;
    /**
     * @var ActiveRecordInterface
     */
    public $model;
    /**
     * @var string
     */
    public $workMode = self::MODE_MANY;

    /**
     * @var bool
     */
    public $showFileAsImage = true;

    /**
     * @var array
     * Список колонок для отображения в Grid, который соответствует полям AttachFileEntity
     *
     */
    public $filesGridColumns = [
        'name',
        'file_extension',
        'file_tags',
        'created_at'
    ];

    public function run(): void
    {
        $viewTemplate = match($this->workMode) {
            self::MODE_MANY => 'many',
            self::MODE_ONE => 'one'
        };

        echo $this->render($viewTemplate, [
            'parentModel' => $this->model,
            'blockTitle' => $this->blockTitle,
            'uploadButtonTitle' => $this->uploadButtonTitle,
            'canDeleted' => $this->canDeleted,
            'showFileAsImage' => $this->showFileAsImage,
            'filesGridColumns' => $this->fileGridColumns,
            'dataProvider' => $this->model->getAttachedFiles(),
            'canAttached' => $this->model->getCanFilesToAttach(),
        ]);
    }
}
