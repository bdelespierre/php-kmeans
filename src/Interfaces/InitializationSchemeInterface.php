<?php

namespace Kmeans\Interfaces;

use Kmeans\Interfaces\ClusterCollectionInterface;
use Kmeans\Interfaces\PointCollectionInterface;

interface InitializationSchemeInterface
{
    public function initializeClusters(PointCollectionInterface $points, int $nbClusters): ClusterCollectionInterface;
}
