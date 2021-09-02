<?php

namespace Kmeans\Interfaces;

interface ClusterizationResultInterface extends \Serializable
{
    public function hasReachedConvergence(): bool;

    /**
     * @return int<0, max>
     */
    public function iterationsCount(): int;

    public function getClusters(): ClusterCollectionInterface;

    public function resume(PointCollectionInterface $newPoints): self;
}
