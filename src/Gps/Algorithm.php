<?php

namespace Kmeans\Gps;

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
                "GPS algorithm can only calculate distance from GPS locations"
            );
        }

        return Math::haversine($pointA->getCoordinates(), $pointB->getCoordinates());
    }

    public function findCentroid(PointCollectionInterface $points): PointInterface
    {
        if (! $points->getSpace() instanceof Space) {
            throw new \InvalidArgumentException(
                "Point collection should consist of GPS coordinates"
            );
        }

        /** @var array<Point> $pointsArray */
        $pointsArray = iterator_to_array($points);

        return $points->getSpace()->makePoint(Math::gpsCentroid(
            array_map(fn (Point $point) => $point->getCoordinates(), $pointsArray)
        ));
    }
}
