<?php

namespace app\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\rbac\rules
 */
final class CheckDataAllRule extends Rule
{
    public $name = 'checkDataAll';

    public function execute($user, $item, $params): bool
    {
        if (
            !isset($params['group'])
            || !in_array($params['group'],  array_keys(Yii::$app->getUser()->getIdentity()->groups))
        ) {
            return false;
        }

        return true;
    }
}