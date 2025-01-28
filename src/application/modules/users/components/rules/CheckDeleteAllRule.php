<?php

namespace app\modules\users\components\rules;

use Yii;
use yii\rbac\Rule;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\components\rules
 */
final class CheckDeleteAllRule extends Rule
{
    public $name = 'checkDeleteAll';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['record_status'])
            || !isset($params['created_gid'])
            || !$params['created_gid']
            || !array_key_exists($params['created_gid'], Yii::$app->getUser()->getIdentity()->groups)
            || $params['record_status']
        ) {
            return false;
        }

        return true;
    }
}