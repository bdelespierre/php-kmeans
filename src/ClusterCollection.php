<?php

namespace Bdelespierre\Kmeans;

use Bdelespierre\Kmeans\Concerns\HasSpaceTrait;
use Bdelespierre\Kmeans\Interfaces\ClusterCollectionInterface;
use Bdelespierre\Kmeans\Interfaces\ClusterInterface;
use Bdelespierre\Kmeans\Interfaces\SpaceInterface;

class ClusterCollection implements ClusterCollectionInterface
{
    use HasSpaceTrait;

    protected \SplObjectStorage $storage;

    public function __construct(SpaceInterface $space)
    {
        $this->setSpace($space);

        $this->storage = new \SplObjectStorage();
    }

    public function has(ClusterInterface $cluster): bool
    {
        return $this->storage->contains($cluster);
    }

    public function add(ClusterInterface $cluster): void
    {
        if ($cluster->getCentroid()->getSpace() !== $this->getSpace()) {
            throw new \InvalidArgumentException(
                "Cannot add cluster to collection: cluster space is not same as collection space"
            );
        }

        $this->storage->attach($cluster);
    }

    public function remove(ClusterInterface $cluster): void
    {
        $this->storage->detach($cluster);
    }

    public function current()
    {
        return $this->storage->current();
    }

    public function key()
    {
        return $this->storage->key();
    }

    public function next(): void
    {
        $this->storage->next();
    }

    public function rewind(): void
    {
        $this->storage->rewind();
    }

    public function valid(): bool
    {
        return $this->storage->valid();
    }

    public function count(): int
    {
        return count($this->storage);
    }
}
