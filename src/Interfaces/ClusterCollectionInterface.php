<?php

namespace Kmeans\Interfaces;

/**
 * @extends \Iterator<ClusterInterface>
 */
interface ClusterCollectionInterface extends SpaceBoundInterface, \Iterator, \Countable
{
    public function contains(ClusterInterface $cluster): bool;

    public function attach(ClusterInterface $cluster): void;

    public function detach(ClusterInterface $cluster): void;
}
