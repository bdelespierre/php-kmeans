<?php

namespace Bdelespierre\Kmeans\Interfaces;

use Bdelespierre\Kmeans\Interfaces\InterfPointInterface;
use Bdelespierre\Kmeans\Interfaces\PointCollectionInterface;
use Bdelespierre\Kmeans\Interfaces\PointInterface;
use Bdelespierre\Kmeans\Interfaces\SpaceInterface;

interface ClusterInterface
{
    public function getCentroid(): PointInterface;

    public function getPoints(): PointCollectionInterface;

    public function attach(PointInterface $point): void;

    public function detach(PointInterface $point): void;
}
