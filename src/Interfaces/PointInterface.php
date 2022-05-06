<?php

namespace Kmeans\Interfaces;

interface PointInterface extends SpaceBoundInterface
{
    /**
     * @return array<float>
     */
    public function getCoordinates(): array;

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $data
     */
    public function setData($data): void;
}
