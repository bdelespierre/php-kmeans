<?php

namespace Bdelespierre\Kmeans\Interfaces;

use Bdelespierre\Kmeans\Interfaces\SpaceInterface;
use Bdelespierre\Kmeans\Interfaces\PointInterface;

interface PointCollectionInterface extends \Iterator, \Countable
{
    public function getSpace(): SpaceInterface;

    public function has(PointInterface $point): bool;

    public function add(PointInterface $point): void;

    public function remove(PointInterface $point): void;
}
