<?php

namespace Bdelespierre\Kmeans\Interfaces;

use Bdelespierre\Kmeans\Interfaces\SpaceInterface;

interface PointInterface
{
    public function getSpace(): SpaceInterface;

    public function getCoordinates(): array;

    public function setCoordinates(array $coordinates): void;

    public function getData();

    public function setData($data): void;
}
