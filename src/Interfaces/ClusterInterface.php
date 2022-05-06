<?php

namespace Kmeans\Interfaces;

interface ClusterInterface extends SpaceBoundInterface
{
    public function getCentroid(): PointInterface;

    public function setCentroid(PointInterface $point): void;

    public function getPoints(): PointCollectionInterface;

    public function attach(PointInterface $point): void;

    public function detach(PointInterface $point): void;
}
