<?php

namespace app\repositories\group;

use app\components\base\{BaseRepositoryInterface};
use app\components\base\BaseAR;
use app\components\base\BaseARInterface;
use app\entities\group\GroupTypeEntity;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\repositories\report
 */
final class GroupTypeBaseRepository implements BaseRepositoryInterface
{
    public static function get(
        int $id,
        array $relations = [],
        bool $active = true
    ): ?BaseARInterface {
        $query = GroupTypeEntity::find()->where(['id' => $id]);
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
        $query = GroupTypeEntity::find()->where($condition);
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
        $query = GroupTypeEntity::find();
        if ($relations) {
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

    public static function getAllBy(
        array $condition,
        array $relations = [],
        bool $asArray = false,
        bool $active = true
    ): ActiveQuery|array {
        $query = GroupTypeEntity::find()->where($condition);
        if ($relations) {
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
}