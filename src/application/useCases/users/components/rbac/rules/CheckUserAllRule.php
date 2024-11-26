<?php

namespace app\useCases\users\components\rbac\rules;

use Yii;
use yii\rbac\Rule;

use app\useCases\users\repositories\user\UserBaseRepository;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\rbac\rules
 */
final class CheckUserAllRule extends Rule
{
    public $name = 'checkUserAll';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['record_status'])
            || !isset($params['id'])
            || !$params['record_status']
            || !$params['id']
        ) {
            return false;
        }

        $cacheKeyAll = Yii::$app->getUser()->id . '_allowedUserAll';
        $users = Yii::$app->cache->getOrSet($cacheKeyAll, function(){
            return array_keys(UserBaseRepository::getAllow(
                groups: Yii::$app->getUser()->getIdentity()->groups
            ));
        }, 3600);

        if ( !in_array($params['id'], $users) ) {
            return false;
        }

        return true;
    }
}