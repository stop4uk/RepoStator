<?php

namespace app\interfaces;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\interfaces
 */
interface ModelInterface
{
    public function getEntity(): BaseARInterface;
    public function getIsNewEntity(): bool;
}
