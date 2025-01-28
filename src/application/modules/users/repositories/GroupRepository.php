<?php

namespace app\modules\users\repositories;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

use app\components\base\{
    BaseAR,
    BaseARInterface,
    BaseRepositoryInterface
};
use app\modules\users\entities\GroupEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\repositories
 */
final class GroupRepository implements BaseRepositoryInterface
{
    public static function get(
        int $id,
        array $relations = [],
        bool $active = true
    ): ?BaseARInterface {
        $query = GroupEntity::find()->where(['id' => $id]);
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
        $query = GroupEntity::find()->where($condition);
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
    ): array {
        $query = GroupEntity::find();
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->where(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asArray ) {
            return ArrayHelper::map($query->all(), 'id', 'name');
        }

        return $query->all();
    }

    public static function getAllBy(
        array $condition,
        array $relations = [],
        bool $asArray = false,
        bool $active = true
    ): ActiveQuery|array {
        $query = GroupEntity::find()->where($condition);
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