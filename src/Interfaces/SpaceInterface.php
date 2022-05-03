<?php

namespace Kmeans\Interfaces;

interface SpaceInterface
{
    public function isEqualTo(self $space): bool;

    /**
     * @param array<mixed> $coordinates
     */
    public function makePoint(array $coordinates): PointInterface;
}
