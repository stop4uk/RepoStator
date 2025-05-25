<?php

namespace app\components\attachedFiles\widgets\attachfile;

use yii\base\Widget;
use yii\db\ActiveRecordInterface;

use app\components\attachedFiles\AttachFileHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\componetns\attachedFiles\widgets\attachfile
 */
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
     * @var string|null
     */
    public $uploadButtonOptions;
    /**
     * @var string|null
     */
    public $uploadButtonHintText;
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
    public $showFileAsImage = false;
    /**
     * @var bool
     */
    public bool $loadInSession = false;
    public bool $showAllFiles = true;
    public string $pjaxIDContainer = 'attachedFileList';

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
        $viewTemplate = match ($this->workMode) {
            self::MODE_MANY => 'many',
            self::MODE_ONE => 'one'
        };

        echo $this->render($viewTemplate, [
            'parentModel' => $this->model,
            'blockTitle' => $this->blockTitle,
            'uploadButtonTitle' => $this->uploadButtonTitle,
            'uploadButtonOptions' => $this->uploadButtonOptions,
            'canDeleted' => $this->canDeleted,
            'showFileAsImage' => $this->showFileAsImage,
            'uploadButtonHintText' => $this->uploadButtonHintText,
            'dataProvider' => $this->model->getAttachedFiles(),
            'canAttached' => $this->model->getCanFilesToAttach(),
            'loadInSession' => $this->loadInSession,
            'showAllFiles' => $this->showAllFiles,
            'filesGridColumns' => $this->filesGridColumns,
            'sessionKey' => AttachFileHelper::getSessionKey($this->model::class),
            'pjaxIDContainer' => $this->pjaxIDContainer,
        ]);
    }
}
