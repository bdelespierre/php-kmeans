<?php

namespace Kmeans\Gps;

use Kmeans\Algorithm as BaseAlgorithm;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Math;

class Algorithm extends BaseAlgorithm
{
    protected function getDistanceBetween(PointInterface $pointA, PointInterface $pointB): float
    {
        if (! $pointA instanceof Point || ! $pointB instanceof Point) {
            throw new \InvalidArgumentException(
                "Expecting \\Kmeans\\GPS\\Point"
            );
        }

        return Math::haversine($pointA->getCoordinates(), $pointB->getCoordinates());
    }

    protected function findCentroid(PointCollectionInterface $points): PointInterface
    {
        if (! $points->getSpace() instanceof Space) {
            throw new \InvalidArgumentException(
                "Point collection should consist of GPS coordinates"
            );
        }

        /** @var array<Point> $points */
        $points = iterator_to_array($points);

        return new Point(Math::gpsCentroid(
            array_map(fn (Point $point) => $point->getCoordinates(), $points)
        ));
    }
}
