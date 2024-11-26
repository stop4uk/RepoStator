<?php

namespace app\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\rbac\rules
 */
final class CheckDeleteGroupRule extends Rule
{
    public $name = 'checkDeleteGroup';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['record_status'])
            || !isset($params['created_gid'])
            || !$params['created_gid']
            || $params['record_status']
            || $params['created_gid'] != Yii::$app->getUser()->getIdentity()->group
        ) {
            return false;
        }

        return true;
    }
}