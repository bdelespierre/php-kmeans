<?php

namespace KMeans\Algorithms;

use KMeans\Interfaces\DistanceAlgorithmInterface;
use KMeans\Interfaces\PointInterface;

class EuclidianDistance implements DistanceAlgorithmInterface
{
    public function getDistanceBetween(PointInterface $pointA, PointInterface $pointB): float
    {
        if ($pointA->getSpace()->getDimention() !== $pointB->getSpace()->getDimention()) {
            throw new \LogicException(
                "Cannot calculate euclidian distance between point of different dimentions"
            );
        }

        $distance = 0;
        $dimention = $pointA->getSpace()->getDimention();
        $coordinatesA = $pointA->getCoordinates();
        $coordinatesB = $pointB->getCoordinates();

        for ($n = 0; $n < $dimention; $n++) {
            $difference = $coordinatesA[$n] - $coordinatesB[$n];
            $distance += $difference ** 2;
        }

        return sqrt($distance);
    }
}
