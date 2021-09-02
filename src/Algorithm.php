<?php

namespace Bdelespierre\Kmeans;

use Bdelespierre\Kmeans\ClusterCollection;
use Bdelespierre\Kmeans\Interfaces\AlgorithmInterface;
use Bdelespierre\Kmeans\Interfaces\ClusterCollectionInterface;
use Bdelespierre\Kmeans\Interfaces\PointCollectionInterface;

class Algorithm implements AlgorithmInterface
{
    public function clusterize(PointCollectionInterface $points, int $clusterCount): ClusterCollectionInterface
    {
        $clusters = new ClusterCollection($points->getSpace());

        // @todo implement

        return $clusters;
    }
}
