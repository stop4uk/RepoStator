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
use app\useCases\reports\entities\ReportConstantEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\repositories\report
 */
final class ConstantBaseRepository implements BaseRepositoryInterface
{
    public static function get(
        int $id,
        array $relations = [],
        bool $active = true
    ): ?BaseARInterface {
        $query = ReportConstantEntity::find()->where(['id' => $id]);
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query->limit(1)->one();
    }

    public static function getBy(
        array $condition,
        array $relations = [],
        bool $active = true
    ): ?BaseARInterface {
        $query = ReportConstantEntity::find()->where($condition);
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query->limit(1)->one();
    }

    public static function getAll(
        array $relations = [],
        bool $asArray = false,
        bool $active = true
    ): ActiveQuery|array {
        $query = ReportConstantEntity::find();
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->where(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asArray ) {
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
        $query = ReportConstantEntity::find()->where($condition);
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asArray ) {
            return ArrayHelper::map($query->all(), 'record', 'name');
        }

        return $query;
    }

    public static function getAllow(
        array $reports,
        array $groups,
        bool $fullInformation = false,
        bool $active = true,
        bool $asQuery = false,
    ): ActiveQuery|array {
        $condition = [
            'or',
            [
                'and',
                ['in', 'created_gid', array_keys($groups)],
                [
                    'or',
                    ['is', 'reports_only', new Expression('null')],
                    ['=', 'reports_only', new Expression("''")],
                    ['REGEXP', 'reports_only', '\b(' . implode('|', array_keys($reports)) . ')\b']
                ]
            ]
        ];

        if ( $groupsParent = Yii::$app->getUser()->getIdentity()->groupsParent ) {
            $condition[] = [
                'and',
                ['in', 'created_gid', $groupsParent],
                [
                    'or',
                    ['is', 'reports_only', new Expression('null')],
                    ['=', 'reports_only', new Expression("''")],
                    ['REGEXP', 'reports_only', '\b(' . implode('|', array_keys($reports)) . ')\b']
                ]
            ];
        }


        $query = ReportConstantEntity::find()
            ->where($condition);

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asQuery ) {
            return $query;
        }
        
        if ( $fullInformation ) {
            return self::formFull($query->all());
        }

        return ArrayHelper::map($query->all(), 'record', 'name');
    }

    private static function formFull($results): array
    {
        if ( $results ) {
            foreach ($results as $constant) {
                $returnArray[$constant->record] = [
                    'name' => $constant->name,
                    'fullName' => $constant->name_full,
                    'union_rules' => $constant->union_rules,
                ];
            }
        }

        return $returnArray ?? [];
    }
}