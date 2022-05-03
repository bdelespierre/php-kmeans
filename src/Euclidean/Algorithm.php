<?php

namespace Kmeans\Euclidean;

use Kmeans\Algorithm as BaseAlgorithm;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Math;

class Algorithm extends BaseAlgorithm
{
    public function getDistanceBetween(PointInterface $pointA, PointInterface $pointB): float
    {
        if (! $pointA instanceof Point || ! $pointB instanceof Point) {
            throw new \InvalidArgumentException(
                "Euclidean Algorithm can only calculate distance between euclidean points"
            );
        }

        return Math::euclideanDist($pointA->getCoordinates(), $pointB->getCoordinates());
    }

    public function findCentroid(PointCollectionInterface $points): PointInterface
    {
        if (! $points->getSpace() instanceof Space) {
            throw new \InvalidArgumentException(
                "Point collection should consist of Euclidean points"
            );
        }

        return $points->getSpace()->makePoint(Math::centroid(
            array_map(fn (PointInterface $point) => $point->getCoordinates(), iterator_to_array($points))
        ));
    }
}
