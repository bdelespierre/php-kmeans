<?php

namespace Kmeans;

use Kmeans\Interfaces\ClusterInterface;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Interfaces\SpaceInterface;

class Cluster implements ClusterInterface
{
    private PointInterface $centroid;
    private PointCollectionInterface $points;

    public function __construct(PointInterface $centroid, PointCollectionInterface $points = null)
    {
        $this->points = $points ?? new PointCollection($centroid->getSpace());
        $this->setCentroid($centroid);
    }

    public function getSpace(): SpaceInterface
    {
        return $this->points->getSpace();
    }

    public function belongsTo(SpaceInterface $space): bool
    {
        return $this->getSpace()->isEqualTo($space);
    }

    public function getCentroid(): PointInterface
    {
        return $this->centroid;
    }

    public function setCentroid(PointInterface $point): void
    {
        if (! $point->belongsTo($this->getSpace())) {
            throw new \LogicException("Cannot set centroid: invalid point space");
        }

        $this->centroid = $point;
    }

    public function getPoints(): PointCollectionInterface
    {
        return $this->points;
    }

    public function attach(PointInterface $point): void
    {
        $this->points->attach($point);
    }

    public function detach(PointInterface $point): void
    {
        $this->points->detach($point);
    }
}
