<?php

namespace app\useCases\users\repositories\user;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

use app\components\base\{
    BaseAR,
    BaseARInterface,
    BaseRepositoryInterface
};
use app\useCases\users\{
    entities\user\UserEntity,
    entities\user\UserGroupEntity
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\repositories\user
 */
final class UserRepository implements BaseRepositoryInterface
{
    public static function get(int $id, array $relations = [], bool $active = true): ?BaseARInterface
    {
        $query = UserEntity::find()->where(['id' => $id]);
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query->limit(1)->one();
    }

    public static function getBy(array $condition, array $relations = [], bool $active = true): ?BaseARInterface
    {
        $query = UserEntity::find()->where($condition);
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        return $query->limit(1)->one();
    }

    public static function getAll(array $relations = [], bool $asArray = false, bool $active = true): ActiveQuery|array
    {
        $query = UserEntity::find();
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->where(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asArray ) {
            return ArrayHelper::map($query->all(), 'id', 'shortName');
        }

        return $query;
    }

    public static function getAllBy(array $condition, array $relations = [], bool $asArray = false, bool $active = true): ActiveQuery|array
    {
        $query = UserEntity::find()->where($condition);
        if ($relations) {
            $query->with($relations);
        }

        if ($active) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $asArray ) {
            return ArrayHelper::map($query->all(), 'id', 'shortName');
        }

        return $query;
    }

    public static function getAllow(
        array $groups,
        bool $active = true,
    ): array {
        $query = UserGroupEntity::find()
            ->with(['user'])
            ->andFilterWhere(['in', 'group_id', array_keys($groups)]);

        if ( $active ) {
            $query->andWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
        }

        if ( $results = $query->all() ) {
            $users = [];
            foreach ($results as $row) {
                $users[$row->user_id] = $row->user->shortName ?? null;
            }

            return $users;
        }

        return [];
    }
}