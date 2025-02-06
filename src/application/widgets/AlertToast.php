<?php

namespace app\widgets;

use Yii;
use yii\bootstrap5\Widget;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\widgets
 */
class AlertToast extends Widget
{
    /**
     * @var string[]
     */
    public $alertTypes = [
        'error'   => 'bg-danger',
        'danger'  => 'bg-danger',
        'success' => 'bg-success',
        'info'    => 'bg-info',
        'warning' => 'bg-warning'
    ];

    /**
     * @var string[]
     */
    public $alertTextTypes = [
        'error'   => 'text-white',
        'danger'  => 'text-white',
        'success' => 'text-white',
        'info'    => 'text-white',
        'warning' => 'text-white'
    ];

    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array) $flash as $i => $message) {
                echo MainBootstrapToast::widget([
                    'body' => $message,
                    'options' => [
                        'id' => $this->getId() . '-' . $type . '-' . $i,
                        'class' => $this->alertTypes[$type] . ' ' . $this->alertTextTypes[$type] . ' '. $appendClass,
                    ]
                ]);
            }

            $session->removeFlash($type);
        }
    }
}
