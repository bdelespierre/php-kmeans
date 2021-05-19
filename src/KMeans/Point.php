<?php

/**
 * This file is part of PHP K-Means
 *
 * Copyright (c) 2014 Benjamin Delespierre
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace KMeans;

use KMeans\Algorithms\EuclidianDistance;
use KMeans\Interfaces\DistanceAlgorithmInterface;
use KMeans\Interfaces\PointInterface;
use KMeans\Interfaces\SpaceInterface;

class Point implements PointInterface, \ArrayAccess
{
    protected $space;
    protected $coordinates;
    protected $distanceAlgo;

    public function __construct(SpaceInterface $space, array $coordinates, DistanceAlgorithmInterface $algo = null)
    {
        $this->space = $space;
        $this->coordinates = $coordinates;
        $this->setDistanceAlgorithm($algo ?: new EuclidianDistance());
    }

    public function setDistanceAlgorithm(DistanceAlgorithmInterface $algo): void
    {
        $this->distanceAlgo = $algo;
    }

    public function toArray(): array
    {
        return [
            'coordinates' => $this->coordinates,
            'data' => isset($this->space[$this]) ? $this->space[$this] : null,
        ];
    }

    public function getDistanceWith(self $point): float
    {
        return $this->distanceAlgo->getDistanceBetween($this, $point);
    }

    public function getClosest(iterable $points): Point
    {
        foreach ($points as $point) {
            $distance = $this->getDistanceWith($point, false);

            if (!isset($minDistance)) {
                $minDistance = $distance;
                $minPoint    = $point;
                continue;
            }

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $minPoint    = $point;
            }
        }

        return $minPoint;
    }

    public function belongsTo(Space $space): bool
    {
        return $this->space === $space;
    }

    public function getSpace(): Space
    {
        return $this->space;
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->coordinates[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->coordinates[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->coordinates[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->coordinates[$offset]);
    }
}
