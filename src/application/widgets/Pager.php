<?php

namespace app\widgets;

use Yii;
use yii\bootstrap5\LinkPager;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\widgets
 *
 * @see LinkPager
 */
class Pager extends LinkPager
{
    public $prevPageLabel = '<span class="bi bi-chevron-left font-weight-bold"></span>';
    public $nextPageLabel = '<span class="bi bi-chevron-right font-weight-bold"></span>';
    public $maxButtonCount = 5;

    public $options = ['class' => 'd-flex justify-content-center mt-3'];
    public $listOptions = ['class' => ['pagination pagination-sm mb-0']];

    public function init()
    {
        parent::init();

        $this->firstPageLabel = Yii::t('views', 'Первая');
        $this->lastPageLabel = Yii::t('views', 'Последняя');

        if ($this->pagination === null) {
            throw new InvalidConfigException('The "pagination" property must be set.');
        }
    }
}

