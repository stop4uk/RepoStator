<?php

namespace app\useCases\reports\repositories;

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
use app\helpers\CommonHelper;
use app\useCases\reports\entities\ReportStructureEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\repositories\report
 */
final class StructureRepository implements BaseRepositoryInterface
{
    public static function get(int $id, array $relations = [], bool $active = true): ?BaseARInterface
    {
        $query = ReportStructureEntity::find()->where(['id' => $id]);
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query->limit(1)->one();
    }

    public static function getBy(array $condition, array $relations = [], bool $active = true): ?BaseARInterface
    {
        $query = ReportStructureEntity::find()->where($condition);
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query->limit(1)->one();
    }

    public static function getAll(array $relations = [], bool $asArray = false, bool $active = true): ActiveQuery|array
    {
        $query = ReportStructureEntity::find();
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->where(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asArray ) {
            return ArrayHelper::map($query->all(), 'id', 'name');
        }

        return $query;
    }

    public static function getAllBy(array $condition, array $relations = [], bool $asArray = false, bool $active = true): ActiveQuery|array
    {
        $query = ReportStructureEntity::find()->where($condition);
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asArray ) {
            return ArrayHelper::map($query->all(), 'id', 'name');
        }

        return $query;
    }

    public static function getAllow(
        array $reports,
        array $groups,
        bool $active = true,
        bool $asQuery = false,
    ): ActiveQuery|array {
        $condition = [
            'or',
            [
                'and',
                ['in', 'created_gid', array_keys($groups)],
                ['in', 'report_id', array_keys($reports)],
                [
                    'or',
                    ['is', 'groups_only', new Expression('null')],
                    ['=', 'groups_only', new Expression("''")],
                    ['REGEXP', 'groups_only', '\b(' . implode('|', array_keys($groups)) . ')\b']
                ]
            ]
        ];

        if ( $groupsParent = Yii::$app->getUser()->getIdentity()->groupsParent ) {
            $condition[] = [
                'and',
                ['in', 'created_gid', $groupsParent],
                ['in', 'report_id', array_keys($reports)],
                [
                    'or',
                        ['is', 'groups_only', new Expression('null')],
                        ['=', 'groups_only', new Expression("''")],
                        ['REGEXP', 'groups_only', '\b(' . implode('|', array_keys($groups)) . ')\b']
                ]
            ];
        }

        $query = ReportStructureEntity::find()
            ->where($condition);

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asQuery ) {
            return $query;
        }

        return ArrayHelper::map($query->all(), 'id', 'name');
    }

    public static function getByReportAndGroup(int $report, int $group): ?BaseARInterface
    {
        $structures = self::getAllBy(['report_id' => $report])->all();

        if ( $structures ) {
            foreach ($structures as $struct) {
                if ( $struct->groups_only && in_array($group, CommonHelper::explodeField($struct->groups_only)) ) {
                    return $struct;
                }
            }

            foreach ($structures as $struct) {
                if ( !$struct->groups_only ) {
                    return $struct;
                }
            }
        }

        return null;
    }
}