<?php

namespace app\modules\users\components\rules;

use Yii;
use yii\rbac\Rule;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\components\rules
 */
final class CheckDeleteMainRule extends Rule
{
    public $name = 'checkDeleteMain';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['record_status'])
            || !isset($params['created_uid'])
            || !$params['created_uid']
            || $params['record_status']
            || $params['created_uid'] != Yii::$app->getUser()->id
        ) {
            return false;
        }

        return true;
    }
}