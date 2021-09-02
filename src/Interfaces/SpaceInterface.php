<?php

namespace Kmeans\Interfaces;

interface SpaceInterface
{
    /**
     * @return int<1, max>
     */
    public function getDimensions(): int;

    public function isEqualTo(self $space): bool;
}
