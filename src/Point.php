<?php

namespace Bdelespierre\Kmeans;

use Bdelespierre\Kmeans\Concerns\HasSpaceTrait;
use Bdelespierre\Kmeans\Interfaces\PointInterface;
use Bdelespierre\Kmeans\Interfaces\SpaceInterface;

class Point implements PointInterface
{
    use HasSpaceTrait;

    private array $coordinates;
    private $data;

    public function __construct(SpaceInterface $space, array $coordinates)
    {
        $this->setSpace($space);
        $this->setCoordinates($coordinates);
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    public function setCoordinates(array $coordinates): void
    {
        $this->coordinates = $this->sanitizeCoordinates($coordinates);
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
     * @codeCoverageIgnore
     */
    private function sanitizeCoordinates(array $coordinates): array
    {
        if (count($coordinates) != $this->space->getDimensions()) {
            throw new \LogicException(sprintf(
                "Invalid set of coordinates: %d coordinates expected, %d coordinates given",
                $this->space->getDimensions(),
                count($coordinates)
            ));
        }

        $coordinates = filter_var_array($coordinates, FILTER_VALIDATE_FLOAT);

        $errors = array_keys($coordinates, false, true);

        if ($errors) {
            throw new \LogicException(sprintf(
                "Invalid set of coordinates: values at offsets [%s] could not be converted to numbers",
                implode(',', $errors)
            ));
        }

        return $coordinates;
    }
}
