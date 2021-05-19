<?php

namespace KMeans\Algorithms;

use KMeans\Interfaces\DistanceAlgorithmInterface;
use KMeans\Interfaces\PointInterface;

class CallbackDistance implements DistanceAlgorithmInterface
{
    private $fn;

    public function __construct(callable $fn)
    {
        $this->fn = $fn;
    }

    public function getDistanceBetween(PointInterface $pointA, PointInterface $pointB): float
    {
        return call_user_func($this->fn, $pointA, $pointB);
    }
}
