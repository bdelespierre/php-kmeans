<?php

namespace Bdelespierre\Kmeans;

use Bdelespierre\Kmeans\Concerns\HasSpaceTrait;
use Bdelespierre\Kmeans\Interfaces\PointCollectionInterface;
use Bdelespierre\Kmeans\Interfaces\PointInterface;
use Bdelespierre\Kmeans\Interfaces\SpaceInterface;

class PointCollection implements PointCollectionInterface
{
    use HasSpaceTrait;

    protected \SplObjectStorage $storage;

    public function __construct(SpaceInterface $space)
    {
        $this->setSpace($space);

        $this->storage = new \SplObjectStorage();
    }

    public function has(PointInterface $point): bool
    {
        return $this->storage->contains($point);
    }

    public function add(PointInterface $point): void
    {
        if ($point->getSpace() !== $this->getSpace()) {
            throw new \InvalidArgumentException(
                "Cannot add point to collection: point space is not same as collection space"
            );
        }

        $this->storage->attach($point);
    }

    public function remove(PointInterface $point): void
    {
        $this->storage->detach($point);
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
