<?php

namespace app\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\rbac\rules
 */
final class CheckMainRule extends Rule
{
    public $name = 'checkMain';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['record_status'])
            || !isset($params['created_gid'])
            || !$params['record_status']
            || $params['created_uid'] != Yii::$app->getUser()->id
        ) {
            return false;
        }

        return true;
    }
}