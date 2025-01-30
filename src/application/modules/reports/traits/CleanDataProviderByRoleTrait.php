<?php

namespace app\modules\reports\traits;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\traits
 */
trait CleanDataProviderByRoleTrait
{
    private function cleanDataProvider(
        ActiveDataProvider $dataProvider,
        string $allDeleteRole,
        string $groupDeleteRole,
        string $mainDeleteRole,
        string $allListRole,
        string $groupListRole,
        string $mainListRole,
    ): ActiveDataProvider {
        $models = $dataProvider->getModels();

        foreach ($models as $index => $model) {
            if (Yii::$app->getUser()->can('admin')) {
                continue;
            }

            $mainGroup = Yii::$app->getUser()->getIdentity()->group;
            $ruleArray = [
                'record_status' => $model->record_status,
                'created_gid' => $model->created_gid,
                'created_uid' => $model->created_uid
            ];
            $mRole = ((bool)$model->record_status) ? $mainListRole : $mainDeleteRole;
            $gRole = ((bool)$model->record_status) ? $groupListRole : $groupDeleteRole;
            $aRole = ((bool)$model->record_status) ? $allListRole : $allDeleteRole;

            if (!in_array($model->created_gid, Yii::$app->getUser()->getIdentity()->groupsParent)) {
                if (
                    $model->created_gid != $mainGroup
                    && Yii::$app->getUser()->can($aRole, $ruleArray)
                ) {
                    unset($models[$index]);
                    continue;
                }

                if (
                    $model->created_gid == $mainGroup
                    && $model->created_uid != Yii::$app->getUser()->id
                    && !Yii::$app->getUser()->can($gRole, $ruleArray)
                ) {
                    unset($models[$index]);
                    continue;
                }

                if (
                    $model->created_uid == Yii::$app->getUser()->id
                    && !Yii::$app->getUser()->can($mRole, $ruleArray)
                ) {
                    unset($models[$index]);
                }
            }
        }

        $dataProvider->setModels($models);
        return $dataProvider;
    }
}