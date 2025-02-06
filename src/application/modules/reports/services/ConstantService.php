<?php

namespace app\modules\reports\services;

use app\components\base\BaseService;
use app\modules\reports\entities\ReportConstantRuleEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\services
 */
final class ConstantService extends BaseService
{
    protected function afterDelete($entity): bool
    {
        $constantRules = ReportConstantRuleEntity::find()
            ->andFilterWhere(['like', 'rule', $entity->record])
            ->all();

        if ($constantRules) {
            foreach ($constantRules as $rule) {
                if ($rule->rule == $entity->record) {
                    $rule->softDelete();
                }
            }
        }

        return true;
    }
}
