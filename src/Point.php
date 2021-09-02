<?php

namespace Kmeans;

use Kmeans\Concerns\HasSpaceTrait;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Interfaces\SpaceInterface;

class Point implements PointInterface
{
    use HasSpaceTrait;

    /**
     * @var array<float>
     */
    private array $coordinates;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @param array<int, float> $coordinates
     */
    public function __construct(SpaceInterface $space, array $coordinates)
    {
        $this->setSpace($space);
        $this->coordinates = $this->sanitizeCoordinates($coordinates);
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @param array<float> $coordinates
     * @return array<float>
     */
    private function sanitizeCoordinates(array $coordinates): array
    {
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

        if ($errors) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid set of coordinates: values at offsets [%s] could not be converted to numbers",
                implode(',', $errors)
            ));
        }

        return $coordinates;
    }
}
