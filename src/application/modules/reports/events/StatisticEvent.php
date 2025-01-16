<?php

namespace app\useCases\reports\events;

use yii\base\Event;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\objects
 */
final class StatisticEvent extends Event
{
    public $jobEntity;
    public $template;
    public $period;
}