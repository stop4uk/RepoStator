<?php

namespace app\widgets;

use yii\i18n\Formatter;
use yii\grid\GridView as BaseGrid;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\widgets
 *
 * @see \yii\grid\GridView
 */
class GridView extends BaseGrid
{
    public $layout = '{items}{pager}';

    public $bordered = false;
    public $striped = true;
    public $responsive = true;
    public $responsiveWrap = false;
    public $persistResize = true;

    public $options = ['class' => 'grid-view table-responsive'];

    public $headerContainer = [
        'class' => ''
    ];

    public $formatter = [
        'class' => Formatter::class,
        'nullDisplay' => ''
    ];

    public $pager = [
        'class' => Pager::class
    ];

    public $showPageSummary = false;
}
