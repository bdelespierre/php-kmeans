<?php

namespace Bdelespierre\Kmeans;

use Bdelespierre\Kmeans\Interfaces\ClusterCollectionInterface;
use Bdelespierre\Kmeans\Interfaces\PointCollectionInterface;

class Algorithm implements AlgorithmInterface
{
    public function run(PointCollectionInterface $points, int $clusterCount): ClusterCollectionInterface
    {
        //
    }
}
