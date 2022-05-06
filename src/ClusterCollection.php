<?php

namespace Kmeans;

use Kmeans\Concerns\HasSpaceTrait;
use Kmeans\Interfaces\ClusterCollectionInterface;
use Kmeans\Interfaces\ClusterInterface;
use Kmeans\Interfaces\SpaceInterface;

class ClusterCollection implements ClusterCollectionInterface
{
    use HasSpaceTrait;

    /**
     * @var \SplObjectStorage<ClusterInterface, null>
     */
    protected \SplObjectStorage $clusters;

    /**
     * @param array<ClusterInterface> $clusters
     */
    public function __construct(SpaceInterface $space, array $clusters = [])
    {
        $this->setSpace($space);
        $this->clusters = new \SplObjectStorage();

        foreach ($clusters as $cluster) {
            $this->attach($cluster);
        }
    }

    // ------------------------------------------------------------------------
    // ClusterCollectionInterface

    public function contains(ClusterInterface $cluster): bool
    {
        return $this->clusters->contains($cluster);
    }

    public function attach(ClusterInterface $cluster): void
    {
        if (! $this->getSpace()->isEqualTo($cluster->getSpace())) {
            throw new \InvalidArgumentException(
                "Cannot add cluster to collection: cluster space is not same as collection space"
            );
        }

        $this->clusters->attach($cluster);
    }

    public function detach(ClusterInterface $cluster): void
    {
        $this->clusters->detach($cluster);
    }

    // ------------------------------------------------------------------------
    // Iterator

    public function current()
    {
        return $this->clusters->current();
    }

    public function key()
    {
        return $this->clusters->key();
    }

    public function next(): void
    {
        $this->clusters->next();
    }

    public function rewind(): void
    {
        $this->clusters->rewind();
    }

    public function valid(): bool
    {
        return $this->clusters->valid();
    }

    // ------------------------------------------------------------------------
    // Countable

    public function count(): int
    {
        return count($this->clusters);
    }
}
