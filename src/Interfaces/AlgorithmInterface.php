<?php

namespace Kmeans\Interfaces;

interface AlgorithmInterface
{
    public function clusterize(PointCollectionInterface $points, int $nbClusters): ClusterCollectionInterface;

    public function getDistanceBetween(PointInterface $pointA, PointInterface $pointB): float;

    public function findCentroid(PointCollectionInterface $points): PointInterface;
}
