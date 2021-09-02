<?php

namespace Kmeans\Interfaces;

interface SpaceBoundInterface
{
    public function getSpace(): SpaceInterface;

    public function belongsTo(SpaceInterface $space): bool;
}
