<?php

namespace app\controllers;

use app\components\base\BaseController;

final class SiteController extends BaseController
{
    public $layout = 'clear';
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}