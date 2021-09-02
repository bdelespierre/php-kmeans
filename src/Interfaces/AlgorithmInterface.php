<?php

namespace Kmeans\Interfaces;

interface AlgorithmInterface
{
    public function clusterize(PointCollectionInterface $points, int $nbClusters): ClusterCollectionInterface;
}
