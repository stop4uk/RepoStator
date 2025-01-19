<?php

namespace stop4uk\users\repositories;

use yii\helpers\ArrayHelper;

use app\components\base\{
    BaseARInterface,
    BaseRepositoryInterface
};
use stop4uk\users\entities\UserGroupEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\repositories\user
 */
final class UserGroupRepository implements BaseRepositoryInterface
{
    public static function get(int $id, array $relations = [], bool $active = true): ?BaseARInterface
    {
        $query = UserGroupEntity::find()->where(['id' => $id]);
        if ($relations) {
            $query->with($relations);
        }

        return $query->limit(1)->one();
    }

    public static function getBy(array $condition, array $relations = [], bool $active = true): ?BaseARInterface
    {
        $query = UserGroupEntity::find()->where($condition);
        if ($relations) {
            $query->with($relations);
        }

        return $query->limit(1)->one();
    }

    public static function getAll(array $relations = [], bool $asArray = false, bool $active = true): array
    {
        $query = UserGroupEntity::find();
        if ($relations) {
            $query->with($relations);
        }

        return $query->all();
    }

    public static function getAllBy(array $condition, array $relations = [], bool $asArray = false, bool $active = true): array
    {
        $query = UserGroupEntity::find()->where($condition);
        if ($relations) {
            $query->with($relations);
        }

        if ( $asArray ) {
            return ArrayHelper::map($query->all(), 'id', 'user_id');
        }

        return $query->all();
    }
}