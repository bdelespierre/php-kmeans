<?php

namespace Kmeans\Gps;

use Kmeans\Concerns\HasDataTrait;
use Kmeans\Concerns\HasSpaceTrait;
use Kmeans\Interfaces\PointInterface;

class Point implements PointInterface
{
    use HasDataTrait;
    use HasSpaceTrait;

    private float $lat;

    private float $long;

    public function __construct(float $lat, float $long)
    {
        $this->validateCoordinates($lat, $long);
        $this->setSpace(Space::singleton());
        $this->lat = $lat;
        $this->long = $long;
    }

    /**
     * @return array{0: float, 1: float}
     */
    public function getCoordinates(): array
    {
        return [$this->lat, $this->long];
    }

    private function validateCoordinates(float $lat, float $long): void
    {
        if ($lat < -90 || $lat > 90 || $long < -180 || $long > 180) {
            throw new \InvalidArgumentException(
                "Invalid GPS coordinates"
            );
        }
    }
}
