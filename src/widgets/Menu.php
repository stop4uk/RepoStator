<?php

namespace app\widgets;

use Yii;
use yii\widgets\Menu as BaseMenu;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\widgets
 *
 * @see \yii\widgets\Menu
 */
class Menu extends BaseMenu
{
    protected function isItemActive($item)
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
        $routeWithoutSlash = substr($route, 1);
        if ( $routeWithoutSlash == $controllerID || $routeWithoutSlash.'/default' == $controllerID ) {
            $activeByController = true;
        }


        $isActive = ($route === $requestUrl || (Yii::$app->homeUrl . $route) === '/' . $requestUrl || $activeByController);
        return $isActive;
    }
}
