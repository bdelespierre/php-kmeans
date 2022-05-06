<?php

namespace Kmeans\Euclidean;

use Kmeans\Concerns\HasDataTrait;
use Kmeans\Concerns\HasSpaceTrait;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Interfaces\SpaceInterface;

class Point implements PointInterface
{
    use HasSpaceTrait;
    use HasDataTrait;

    /**
     * @var array<float>
     */
    private array $coordinates;

    /**
     * @param array<int, float> $coordinates
     */
    public function __construct(SpaceInterface $space, array $coordinates)
    {
        if (! $space instanceof Space) {
            throw new \LogicException(
                "An euclidean point must belong to an euclidean space"
            );
        }

        $this->setSpace($space);
        $this->coordinates = $this->sanitizeCoordinates($coordinates);
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    /**
     * @param array<float> $coordinates
     * @return array<float>
     */
    private function sanitizeCoordinates(array $coordinates): array
    {
        assert($this->space instanceof Space);
        if (count($coordinates) != $this->space->getDimensions()) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid set of coordinates: %d coordinates expected, %d given",
                $this->space->getDimensions(),
                count($coordinates)
            ));
        }

        $coordinates = filter_var_array($coordinates, FILTER_VALIDATE_FLOAT);
        assert(is_array($coordinates));
        $errors = array_keys($coordinates, false, true);

        if (! empty($errors)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid set of coordinates: values at offsets [%s] could not be converted to numbers",
                implode(',', $errors)
            ));
        }

        return $coordinates;
    }
}
