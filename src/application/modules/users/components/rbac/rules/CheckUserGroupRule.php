<?php

namespace app\modules\users\components\rbac\rules;

use app\modules\users\repositories\UserRepository;
use Yii;
use yii\rbac\Rule;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\components\rbac\rules
 */
final class CheckUserGroupRule extends Rule
{
    public $name = 'checkUserGroup';

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

        $cacheKeyAll = Yii::$app->getUser()->id . '_allowedUserGroup';
        $users = Yii::$app->cache->getOrSet($cacheKeyAll, function(){
            return array_keys(UserRepository::getAllow(
                groups: [Yii::$app->getUser()->getIdentity()->group => Yii::$app->getUser()->getIdentity()->group]
            ));
        }, 3600);

        if (!in_array($params['id'], $users)) {
            return false;
        }

        return true;
    }
}