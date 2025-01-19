<?php

namespace app\components\events\handlers;

use Yii;
use yii\base\{
    Application,
    BootstrapInterface,
    Event
};

use app\components\events\dispatchers\{
    UserEventDispatcher,
    AuthEventDispatcher,
    ProfileEventDispatcher
};
use app\useCases\users\services\{
    AuthService,
    ProfileService,
    UserService
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\handlers
 */
final class WebEventHandler implements BootstrapInterface
{
    public function bootstrap($app): void
    {
//        Event::on(Application::class, Application::EVENT_BEFORE_ACTION, function($event) use ($app) {
//            $actionID = $event->action->id;
//            $controllerID = $event->action->controller->id;
//            $maintenanceMode = (bool)$app->settings->get('system', 'app_maintenance');
//
//            if (
//                $maintenanceMode
//                && !$app->getUser()->can('admin')
//                && !in_array($actionID, ['login', 'logout'])
//                && $controllerID != 'offline'
//            ) {
//                return $app->getResponse()->redirect(["/offline"]);
//            }
//
//            if (
//                !Yii::$app->getUser()->isGuest
//                && !$maintenanceMode
//                && Yii::$app->getUser()->getIdentity()->needChangePassword
//                && $actionID != 'changepassword'
//            ) {
//                return $app->getResponse()->redirect(["/profile/changepassword"]);
//            }
//        });
    }
}
