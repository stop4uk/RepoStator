<?php

namespace app\repositories\report;

use app\components\base\{BaseRepositoryInterface};
use app\components\base\BaseAR;
use app\components\base\BaseARInterface;
use app\entities\report\ReportEntity;
use Yii;
use yii\db\{ActiveQuery, Expression};
use yii\helpers\ArrayHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\repositories\report
 */
final class ReportBaseRepository implements BaseRepositoryInterface
{
    public static function get(int $id, array $relations = [], bool $active = true): ?BaseARInterface
    {
        $query = ReportEntity::find()->where(['id' => $id]);
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
        $query = ReportEntity::find()->where($condition);
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
        $query = ReportEntity::find();
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
        $query = ReportEntity::find()->where($condition);
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
        array $groups,
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
                [
                    'or',
                    ['is', 'groups_only', new Expression('null')],
                    ['=', 'groups_only', new Expression("''")],
                    ['REGEXP', 'groups_only', '\b(' . implode('|', array_keys($groupsParent)) . ')\b']
                ]
            ];
        }

        $query = ReportEntity::find()
            ->where($condition);

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asQuery ) {
            return $query;
        }

        return ArrayHelper::map($query->all(), 'id', 'name');
    }
}