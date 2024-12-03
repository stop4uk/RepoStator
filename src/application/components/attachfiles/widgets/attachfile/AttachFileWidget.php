<?php

namespace common\attachfiles\widgets\attachfile;

use yii\base\{
    Model,
    Widget
};
use yii\db\ActiveRecordInterface;

final class AttachFileWidget extends Widget
{
    const WORKMODE_DOCS = 'docs';
    const WORKMODE_ONEPHOTO = 'onePhoto';

    public string $blockTitle = 'Документы к заказу';
    public string $uploadButtonTitle = 'Прикрепить документы';
    public bool $canDeleted = true;
    public ActiveRecordInterface|Model|null $model;
    public $workMode = self::WORKMODE_DOCS;

    public function run()
    {
        $viewTemplate = match($this->workMode) {
            self::WORKMODE_DOCS => 'index',
            self::WORKMODE_ONEPHOTO => 'onePhoto'
        };

        echo $this->render($viewTemplate, [
            'parentModel' => $this->model,
            'blockTitle' => $this->blockTitle,
            'uploadButtonTitle' => $this->uploadButtonTitle,
            'dataProvider' => $this->model->getAttachedFiles(),
            'canAttached' => $this->model->getCanFilesToAttach(),
            'canDeleted' => $this->canDeleted
        ]);
    }
}
