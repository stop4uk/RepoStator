<?php

namespace app\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\rbac\rules
 */
final class CheckDeleteAllRule extends Rule
{
    public $name = 'checkDeleteAll';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['record_status'])
            || !isset($params['created_gid'])
            || $params['record_status']
            || !in_array($params['created_gid'],  array_keys(Yii::$app->getUser()->getIdentity()->groups))
        ) {
            return false;
        }

        return true;
    }
}