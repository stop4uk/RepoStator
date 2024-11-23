<?php

namespace app\helpers;

use Yii;
use yii\web\HttpException;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers
 */
class RbacHelper
{
    public static function getOnlyActiveRecordsState(array $permissions): bool
    {
        if ( Yii::$app->getUser()->can('admin') ) {
            return false;
        }

        $userPermissions = Yii::$app->getAuthManager()->getPermissionsByUser(Yii::$app->getUser()->id);
        foreach ($permissions as $permission) {
            if ( in_array($permission, array_keys($userPermissions)) ) {
                return false;
            }
        }

        return true;
    }

    public static function getAllowGroupsArray(string $permission): array
    {
        $mainGroup = Yii::$app->getUser()->getIdentity()->group;
        $allowGroups = [];

        if ( Yii::$app->getUser()->can('admin') ) {
            return Yii::$app->getUser()->getIdentity()->groups;
        }

        if ( $mainGroup ) {
            $allowGroups = [$mainGroup => Yii::$app->getUser()->getIdentity()->groups[$mainGroup]];

            if ( Yii::$app->getUser()->can($permission) ) {
                $allowGroups = Yii::$app->getUser()->getIdentity()->groups;
            }
        }

        return $allowGroups;
    }
}
