<?php

namespace Kmeans\Interfaces;

interface SpaceInterface
{
    public function isEqualTo(self $space): bool;
}
