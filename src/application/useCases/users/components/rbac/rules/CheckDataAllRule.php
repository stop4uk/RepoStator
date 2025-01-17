<?php

namespace app\useCases\users\components\rbac\rules;

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
            || !$params['group']
            || !array_key_exists($params['group'], Yii::$app->getUser()->getIdentity()->groups)
        ) {
            return false;
        }

        return true;
    }
}