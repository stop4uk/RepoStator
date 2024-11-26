<?php

namespace app\useCases\reports\repositories;

use yii\db\ActiveQuery;

use app\components\{
    base\BaseRepositoryInterface,
    base\BaseAR,
    base\BaseARInterface
};
use app\useCases\reports\entities\ReportDataEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\repositories\report
 */
final class DataBaseRepository implements BaseRepositoryInterface
{
    public static function get(int $id, array $relations = [], bool $active = true): ?BaseARInterface
    {
        $query = ReportDataEntity::find()->where(['id' => $id]);
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
        $query = ReportDataEntity::find()->where($condition);
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
        $query = ReportDataEntity::find();
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->where(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query;
    }

    public static function getAllBy(array $condition, array $relations = [], bool $asArray = false, bool $active = true): ActiveQuery|array
    {
        $query = ReportDataEntity::find()->where($condition);
        if ( $relations ) {
            $query->with($relations);
        }

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query;
    }

    public static function getAllow(
        array $groups,
        bool $active = true,
        bool $asQuery = false,
    ): ActiveQuery|array {
        $query = ReportDataEntity::find()
            ->where(['in', 'group_id', array_keys($groups)]);

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asQuery ) {
            return $query;
        }

        return $query->all();
    }
}