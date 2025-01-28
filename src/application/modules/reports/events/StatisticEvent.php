<?php

namespace app\modules\reports\events;

use yii\base\Event;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\events
 */
final class StatisticEvent extends Event
{
    public $jobEntity;
    public $template;
    public $period;
}