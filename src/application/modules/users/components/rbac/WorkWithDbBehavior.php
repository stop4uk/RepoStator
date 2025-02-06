<?php

namespace app\modules\users\components\rbac;

use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\components\rbac
 */
final class WorkWithDbBehavior extends Behavior
{
    public string|array $createRole = '';
    public array $createRoleParams = [];

    public string|array $updateRole = '';
    public array $updateRoleParams = [];

    public function events(): array
    {
        return array_fill_keys(
            [BaseActiveRecord::EVENT_BEFORE_INSERT, BaseActiveRecord::EVENT_BEFORE_UPDATE],
            'checkAccess'
        );
    }

    public function checkAccess($event): bool
    {
        if (
            (
                YII_ENV != YII_ENV_TEST
                && !Yii::$app instanceOf yii\console\Application
                && !Yii::$app->getUser()->isGuest
            )
            && (
                (
                    $this->createRole
                    && $event->name == BaseActiveRecord::EVENT_BEFORE_INSERT
                    && !$this->checkRole($this->createRole, $this->createRoleParams)
                )
                || (
                    $this->updateRole
                    && $event->name == BaseActiveRecord::EVENT_BEFORE_UPDATE
                    && !$this->checkRole($this->updateRole, $this->updateRoleParams)
                )
            )
        ) {
            return false;
        }

        return true;
    }

    private function checkRole(string|array $roles, array $params = []): bool
    {
        if (is_array($roles)) {
            foreach($roles as $role) {
                if (Yii::$app->getUser()->can($role, $params)) {
                    return true;
                }
            }
        } elseif (Yii::$app->getUser()->can($roles, $params)) {
            return true;
        }

        return false;
    }
}
