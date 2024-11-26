<?php

namespace app\components\base;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\interfaces
 */
interface BaseModelInterface
{
    public function getEntity(): BaseARInterface;
    public function getIsNewEntity(): bool;
}
