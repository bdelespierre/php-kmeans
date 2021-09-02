<?php

namespace Kmeans;

use Kmeans\Cluster;
use Kmeans\ClusterCollection;
use Kmeans\Interfaces\AlgorithmInterface;
use Kmeans\Interfaces\ClusterCollectionInterface;
use Kmeans\Interfaces\ClusterInterface;
use Kmeans\Interfaces\InitializationSchemeInterface;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;

class Algorithm implements AlgorithmInterface
{
    private InitializationSchemeInterface $initScheme;
    /** @var array<callable> */
    private array $iterationCallbacks = [];


    public function __construct(InitializationSchemeInterface $initScheme)
    {
        $this->initScheme = $initScheme;
    }

    public function registerIterationCallback(callable $callback): void
    {
        $this->iterationCallbacks[] = $callback;
    }

    public function clusterize(PointCollectionInterface $points, int $nbClusters): ClusterCollectionInterface
    {
        try {
            // initialize clusters
            $clusters = $this->initScheme->initializeClusters($points, $nbClusters);
        } catch (\Exception $e) {
            throw new \RuntimeException("Cannot initialize clusters", 0, $e);
        }

        // iterate until convergence is reached
        do {
            $this->invokeIterationCallbacks($clusters);
        } while ($this->iterate($clusters));

        // clustering is done.
        return $clusters;
    }

    protected function iterate(ClusterCollectionInterface $clusters): bool
    {
        /** @var \SplObjectStorage<ClusterInterface, null> */
        $changed = new \SplObjectStorage();

        // calculate proximity amongst points and clusters
        foreach ($clusters as $cluster) {
            foreach ($cluster->getPoints() as $point) {
                // find the closest cluster
                $closest = $this->getClosestCluster($clusters, $point);

                if ($closest !== $cluster) {
                    // move the point from its current cluster to its closest
                    $cluster->detach($point);
                    $closest->attach($point);

                    // flag both clusters as changed
                    $changed->attach($cluster);
                    $changed->attach($closest);
                }
            }
        }

        // update changed clusters' centroid
        foreach ($changed as $cluster) {
            $cluster->setCentroid($this->findCentroid($cluster->getPoints()));
        }

        // return true if something changed during this iteration
        return count($changed) > 0;
    }

    protected function getClosestCluster(ClusterCollectionInterface $clusters, PointInterface $point): ClusterInterface
    {
        $min = null;
        $closest = null;

        foreach ($clusters as $cluster) {
            $distance = $this->getDistanceBetween($point, $cluster->getCentroid());

            if (is_null($min) || $distance < $min) {
                $min = $distance;
                $closest = $cluster;
            }
        }

        assert($closest !== null);
        return $closest;
    }

    protected function getDistanceBetween(PointInterface $pointA, PointInterface $pointB): float
    {
        return euclidean_dist($pointA->getCoordinates(), $pointB->getCoordinates());
    }

    protected function findCentroid(PointCollectionInterface $points): PointInterface
    {
        return new Point($points->getSpace(), find_centroid(
            array_map(fn ($point) => $point->getCoordinates(), iterator_to_array($points))
        ));
    }

    protected function invokeIterationCallbacks(ClusterCollectionInterface $clusters): void
    {
        foreach ($this->iterationCallbacks as $callback) {
            $callback($this, $clusters);
        }
    }
}
