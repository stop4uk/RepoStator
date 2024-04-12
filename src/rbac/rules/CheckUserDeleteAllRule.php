<?php

namespace app\rbac\rules;

use Yii;
use yii\rbac\Rule;

use app\repositories\user\UserRepository;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\rbac\rules
 */
final class CheckUserDeleteAllRule extends Rule
{
    public $name = 'checkUserDeleteAll';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['record_status'])
            || !isset($params['id'])
            || $params['record_status']
        ) {
            return false;
        }

        $cacheKeyAll = Yii::$app->getUser()->id . '_allowedUserDeleteAll';
        $users = Yii::$app->cache->getOrSet($cacheKeyAll, function(){
            return array_keys(UserRepository::getAllow(
                groups: Yii::$app->getUser()->getIdentity()->group,
                active: false
            ));
        }, 3600);

        if ( !in_array($params['id'], $users) ) {
            return false;
        }

        return true;
    }
}