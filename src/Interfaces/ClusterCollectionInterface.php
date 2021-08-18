<?php

namespace Bdelespierre\Kmeans\Interfaces;

use Bdelespierre\Kmeans\Interfaces\ClusterInterface;
use Bdelespierre\Kmeans\Interfaces\SpaceInterface;

interface ClusterCollectionInterface extends \Iterator, \Countable
{
    public function getSpace(): SpaceInterface;

    public function has(ClusterInterface $cluster): bool;

    public function add(ClusterInterface $cluster): void;

    public function remove(ClusterInterface $cluster): void;
}
