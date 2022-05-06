<?php

namespace Kmeans\Concerns;

use Kmeans\Interfaces\SpaceInterface;

trait HasSpaceTrait
{
    protected SpaceInterface $space;

    private function setSpace(SpaceInterface $space): void
    {
        $this->space = $space;
    }

    public function getSpace(): SpaceInterface
    {
        return $this->space;
    }

    public function belongsTo(SpaceInterface $space): bool
    {
        return $this->getSpace()->isEqualTo($space);
    }
}
