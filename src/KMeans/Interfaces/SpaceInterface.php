<?php

namespace KMeans\Interfaces;

use KMeans\Interfaces\DistanceAlgorithmInterface;

interface SpaceInterface
{
    public function getDimention(): int;

    public function setDistanceAlgorithm($algo): void;
}
