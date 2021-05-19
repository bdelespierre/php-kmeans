<?php

namespace KMeans\Interfaces;

use KMeans\Interfaces\DistanceAlgorithmInterface;
use KMeans\Interfaces\SpaceInterface;

interface PointInterface
{
    public function getSpace(): SpaceInterface;

    public function getCoordinates(): array;

    public function setDistanceAlgorithm(DistanceAlgorithmInterface $algo): void;
}
