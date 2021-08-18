<?php

namespace Bdelespierre\Kmeans;

use Bdelespierre\Kmeans\Interfaces\SpaceInterface;

class Space implements SpaceInterface
{
    protected $dimensions;

    public function __construct(int $dimensions)
    {
        if ($dimensions < 1) {
            throw new \LogicException("Dimensions cannot be null or negative");
        }

        $this->dimensions = $dimensions;
    }

    public function getDimensions(): int
    {
        return $this->dimensions;
    }
}
