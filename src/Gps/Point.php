<?php

namespace Kmeans\Gps;

use Kmeans\Point as BasePoint;

/**
 * @method array{0: float, 1: float} getCoordinates()
 */
class Point extends BasePoint
{
    /**
     * @param array<float> $coordinates
     */
    public function __construct(array $coordinates)
    {
        $this->validateCoordinates($coordinates);

        parent::__construct(new Space(), $coordinates);
    }

    /**
     * @param array<float> $coordinates
     */
    private function validateCoordinates(array $coordinates): void
    {
        if (count($coordinates) != 2) {
            throw new \InvalidArgumentException(
                "Invalid GPS coordinates"
            );
        }

        list($lat, $long) = $coordinates;

        if ($lat < -90 || $lat > 90 || $long < -180 || $long > 180) {
            throw new \InvalidArgumentException(
                "Invalid GPS coordinates"
            );
        }
    }
}
