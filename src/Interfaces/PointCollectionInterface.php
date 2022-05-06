<?php

namespace Kmeans\Interfaces;

/**
 * @extends \Iterator<PointInterface>
 */
interface PointCollectionInterface extends SpaceBoundInterface, \Iterator, \Countable
{
    public function contains(PointInterface $point): bool;

    public function attach(PointInterface $point): void;

    public function detach(PointInterface $point): void;
}
