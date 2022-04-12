<?php

namespace Kmeans\Gps;

use Kmeans\Interfaces\SpaceInterface;

class Space implements SpaceInterface
{
    public function isEqualTo(SpaceInterface $other): bool
    {
        return $other instanceof self;
    }
}
