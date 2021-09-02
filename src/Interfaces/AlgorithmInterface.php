<?php

namespace Bdelespierre\Kmeans\Interfaces;

use Bdelespierre\Kmeans\Interfaces\ClusterCollectionInterface;
use Bdelespierre\Kmeans\Interfaces\PointCollectionInterface;

interface AlgorithmInterface
{
    public function clusterize(PointCollectionInterface $points, int $clusterCount): ClusterCollectionInterface;
}
