<?php

namespace app\modules\reports\repositories;

use Yii;
use yii\db\{
    ActiveQuery,
    Expression
};
use yii\helpers\ArrayHelper;

use app\components\{
    base\BaseRepositoryInterface,
    base\BaseAR,
    base\BaseARInterface
};
use app\modules\reports\entities\ReportConstantRuleEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\repositories
 */
final class ConstantruleRepository implements BaseRepositoryInterface
{
    public static function get(
        int $id,
        array $relations = [],
        bool $active = true
    ): ?BaseARInterface {
        $query = ReportConstantRuleEntity::find()->where(['id' => $id]);
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query->limit(1)->one();
    }

    public static function getBy(
        array $condition,
        array $relations = [],
        bool $active = true
    ): ?BaseARInterface {
        $query = ReportConstantRuleEntity::find()->where($condition);
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query->limit(1)->one();
    }

    public static function getAll(
        array $relations = [],
        bool $asArray = false,
        bool $active = true
    ): ActiveQuery|array {
        $query = ReportConstantRuleEntity::find();
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->where(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ($asArray) {
            return ArrayHelper::map($query->all(), 'record', 'name');
        }

        return $query;
    }

    public static function getAllBy(
        array $condition,
        array $relations = [],
        bool $asArray = false,
        bool $active = true
    ): ActiveQuery|array {
        $query = ReportConstantRuleEntity::find()->where($condition);
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ($asArray) {
            return ArrayHelper::map($query->all(), 'record', 'name');
        }

        return $query;
    }

    public static function getAllow(
        array $reports,
        array $groups,
        bool $active = true,
        bool $asQuery = false,
    ): ActiveQuery|array {
        $parentGroups = Yii::$app->getUser()->getIdentity()->groupsParent;
        $groupsForSearch = match((bool)$parentGroups) {
            true => ArrayHelper::merge(array_keys($groups), array_keys($parentGroups)),
            false => array_keys($groups)
        };

        $query = ReportConstantRuleEntity::find()
            ->where([
                'or',
                [
                    'and',
                    ['in', 'created_gid', $groupsForSearch],
                    [
                        'or',
                        ['in', 'report_id', array_keys($reports)],
                        ['is', 'report_id', new Expression('null')],
                        ['=', 'report_id', new Expression("''")],
                    ],
                    [
                        'or',
                        ['is', 'groups_only', new Expression('null')],
                        ['=', 'groups_only', new Expression("''")],
                        ['REGEXP', 'groups_only', '(' . implode('|', array_keys($groups)) . ')']
                    ]
                ]
        ]);

        if ($active) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ($asQuery) {
            return $query;
        }

        return ArrayHelper::map($query->all(), 'record', 'name');
    }
}