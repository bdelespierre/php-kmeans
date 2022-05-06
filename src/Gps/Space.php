<?php

namespace Kmeans\Gps;

use Kmeans\Interfaces\PointInterface;
use Kmeans\Interfaces\SpaceInterface;

class Space implements SpaceInterface
{
    public static function singleton(): self
    {
        static $space = new self();

        return $space;
    }

    public function isEqualTo(SpaceInterface $other): bool
    {
        return $other instanceof self;
    }

    /**
     * @param array{0: float, 1: float} $coordinates
     */
    public function makePoint(array $coordinates): PointInterface
    {
        return new Point(...$coordinates);
    }
}
