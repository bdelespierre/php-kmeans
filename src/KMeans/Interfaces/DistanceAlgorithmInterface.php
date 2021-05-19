<?php

namespace KMeans\Interfaces;

use KMeans\Interfaces\PointInterface;

interface DistanceAlgorithmInterface
{
    public function getDistanceBetween(PointInterface $pointA, PointInterface $pointB): float;
}
