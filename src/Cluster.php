<?php

namespace Bdelespierre\Kmeans;

use Bdelespierre\Kmeans\Interfaces\ClusterInterface;
use Bdelespierre\Kmeans\Interfaces\PointCollectionInterface;
use Bdelespierre\Kmeans\Interfaces\PointInterface;

class Cluster implements ClusterInterface
{
    private PointInterface $centroid;
    private PointCollectionInterface $points;

    public function __construct(PointInterface $centroid, PointCollectionInterface $points = null)
    {
        $this->centroid = $centroid;
        $this->points = $points ?? new PointCollection($centroid->getSpace());
    }

    public function getCentroid(): PointInterface
    {
        return $this->centroid;
    }

    public function getPoints(): PointCollectionInterface
    {
        return $this->points;
    }

    public function attach(PointInterface $point): void
    {
        $this->points->add($point);
    }

    public function detach(PointInterface $point): void
    {
        $this->points->remove($point);
    }
}
