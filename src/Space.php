<?php

namespace Kmeans;

use Kmeans\Interfaces\SpaceInterface;

class Space implements SpaceInterface
{
    /**
     * @var int<1, max>
     */
    protected int $dimensions;

    public function __construct(int $dimensions)
    {
        if ($dimensions < 1) {
            throw new \InvalidArgumentException("Invalid space dimentions: {$dimensions}");
        }

        $this->dimensions = $dimensions;
    }

    public function getDimensions(): int
    {
        return $this->dimensions;
    }

    public function isEqualTo(SpaceInterface $space): bool
    {
        return $this->getDimensions() == $space->getDimensions();
    }
}
