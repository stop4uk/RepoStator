<?php

namespace app\components\base;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\componetns\base
 */
interface BaseRepositoryInterface
{
    public static function get(
        int $id,
        array $relations,
        bool $active
    );

    public static function getBy(
        array $condition,
        array $relations,
        bool $active
    );

    public static function getAll(
        array $relations,
        bool $asArray,
        bool $active
    );

    public static function getAllBy(
        array $condition,
        array $relations,
        bool $asArray,
        bool $active
    );
}
