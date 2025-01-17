<?php

namespace app\useCases\users\components\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\rbac\rules
 */
final class CheckAllRule extends Rule
{
    public $name = 'checkAll';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['record_status'])
            || !isset($params['created_gid'])
            || !$params['record_status']
            || !$params['created_gid']
            || !array_key_exists($params['created_gid'],  Yii::$app->getUser()->getIdentity()->groups)
        ) {
            return false;
        }

        return true;
    }
}