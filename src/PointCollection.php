<?php

namespace Kmeans;

use Kmeans\Concerns\HasSpaceTrait;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Interfaces\SpaceInterface;

class PointCollection implements PointCollectionInterface
{
    use HasSpaceTrait;

    /**
     * @var \SplObjectStorage<PointInterface, null>
     */
    protected \SplObjectStorage $points;

    /**
     * @param array<PointInterface> $points
     */
    public function __construct(SpaceInterface $space, array $points = [])
    {
        $this->setSpace($space);
        $this->points = new \SplObjectStorage();

        foreach ($points as $point) {
            $this->attach($point);
        }
    }

    public function contains(PointInterface $point): bool
    {
        return $this->points->contains($point);
    }

    public function attach(PointInterface $point): void
    {
        if (! $point->belongsTo($this->getSpace())) {
            throw new \InvalidArgumentException(
                "Cannot add point to collection: point doesn't belong to the same space as collection"
            );
        }

        $this->points->attach($point);
    }

    public function detach(PointInterface $point): void
    {
        $this->points->detach($point);
    }

    public function current(): PointInterface
    {
        return $this->points->current();
    }

    public function key(): int
    {
        return $this->points->key();
    }

    public function next(): void
    {
        $this->points->next();
    }

    public function rewind(): void
    {
        $this->points->rewind();
    }

    public function valid(): bool
    {
        return $this->points->valid();
    }

    public function count(): int
    {
        return count($this->points);
    }
}
