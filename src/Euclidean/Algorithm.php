<?php

namespace Kmeans\Euclidean;

use Kmeans\Algorithm as BaseAlgorithm;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Math;

class Algorithm extends BaseAlgorithm
{
    protected function getDistanceBetween(PointInterface $pointA, PointInterface $pointB): float
    {
        return Math::euclideanDist($pointA->getCoordinates(), $pointB->getCoordinates());
    }

    protected function findCentroid(PointCollectionInterface $points): PointInterface
    {
        return new Point($points->getSpace(), Math::centroid(
            array_map(fn (PointInterface $point) => $point->getCoordinates(), iterator_to_array($points))
        ));
    }
}
