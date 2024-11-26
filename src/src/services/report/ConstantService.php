<?php

namespace app\services\report;

use app\base\BaseService;
use app\entities\report\ReportConstantRuleEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\services\report
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
