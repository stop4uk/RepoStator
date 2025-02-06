<?php

namespace app\widgets;

use Yii;
use yii\widgets\Menu as BaseMenu;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\widgets
 */
class Menu extends BaseMenu
{
    protected function isItemActive($item): bool
    {
        if (parent::isItemActive($item)) {
            return true;
        }

        if (!isset($item['url'])) {
            return false;
        }

        $route = null;
        $itemUrl = $item['url'];
        $requestUrl = Yii::$app->request->getUrl();

        if (is_array($itemUrl) && isset($itemUrl[0])) {
            $route = $itemUrl[0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
        } else {
            $route = $itemUrl;
        }

        $activeByController = false;
        $controllerID = Yii::$app->controller->id;
        $actionID = Yii::$app->controller->action->id;
        $routeWithoutSlash = substr($route, 1);
        $routeWithoutSlashWithDefault = $routeWithoutSlash . '/default';
        $routeWithAction = $route . '/' . $actionID;

        if (
            $routeWithoutSlash == $controllerID
            || $routeWithoutSlashWithDefault == $controllerID
            || $routeWithAction == Yii::$app->getRequest()->getUrl()
            || $routeWithAction == explode('?', Yii::$app->getRequest()->getUrl())[0]
        ) {
            $activeByController = true;
        }

        return (
            $route === $requestUrl
            || (Yii::$app->homeUrl . $route) === '/' . $requestUrl
            || $activeByController
        );
    }
}
