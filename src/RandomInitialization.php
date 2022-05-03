<?php

namespace Kmeans;

use Kmeans\Interfaces\ClusterCollectionInterface;
use Kmeans\Interfaces\InitializationSchemeInterface;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;

class RandomInitialization implements InitializationSchemeInterface
{
    /**
     * @throws \InvalidArgumentException when $nbClusters is lesser than 1
     */
    public function initializeClusters(PointCollectionInterface $points, int $nbClusters): ClusterCollectionInterface
    {
        // validate cluster count
        if ($nbClusters < 1) {
            throw new \InvalidArgumentException("Invalid cluster count: {$nbClusters}");
        }

        $clusters = new ClusterCollection($points->getSpace());

        // initialize N clusters with a random point
        for ($n = 0; $n < $nbClusters; $n++) {
            // assign all points to the first cluster only
            $clusters->attach(new Cluster($this->getRandomPoint($points), $n == 0 ? $points : null));
        }

        return $clusters;
    }

    protected function getRandomPoint(PointCollectionInterface $points): PointInterface
    {
        if (count($points) == 0) {
            throw new \LogicException("Unable to pick a random point out of an empty point collection");
        }

        $num = mt_rand(0, count($points) - 1);
        foreach ($points as $i => $point) {
            if ($i > $num) {
                break;
            }
        }

        assert(isset($point));
        return $point;
    }
}
