<?php

namespace Bdelespierre\Kmeans\Concerns;

use Bdelespierre\Kmeans\Interfaces\SpaceInterface;

/**
 * @codeCoverageIgnore
 */
trait HasSpaceTrait
{
    protected SpaceInterface $space;

    private function setSpace(SpaceInterface $space)
    {
        $this->space = $space;
    }

    public function getSpace(): SpaceInterface
    {
        return $this->space;
    }
}
