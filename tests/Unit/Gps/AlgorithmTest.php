<?php

namespace Tests\Unit\Gps;

use Kmeans\Euclidean\Point as EuclideanPoint;
use Kmeans\Euclidean\Space as EuclideanSpace;
use Kmeans\Gps\Algorithm;
use Kmeans\Gps\Point;
use Kmeans\Gps\Space;
use Kmeans\Interfaces\AlgorithmInterface;
use Kmeans\Interfaces\InitializationSchemeInterface;
use Kmeans\Interfaces\SpaceInterface;
use Kmeans\PointCollection;
use Mockery;
use Tests\Unit\AlgorithmTest as BaseAlgorithmTest;

/**
 * @covers \Kmeans\Gps\Algorithm
 * @covers \Kmeans\Algorithm
 * @uses \Kmeans\Cluster
 * @uses \Kmeans\ClusterCollection
 * @uses \Kmeans\Concerns\HasDataTrait
 * @uses \Kmeans\Concerns\HasSpaceTrait
 * @uses \Kmeans\Euclidean\Point
 * @uses \Kmeans\Euclidean\Space
 * @uses \Kmeans\Gps\Point
 * @uses \Kmeans\Gps\Space
 * @uses \Kmeans\Math
 * @uses \Kmeans\PointCollection
 * @phpstan-import-type ScenarioData from BaseAlgorithmTest
 */
class AlgorithmTest extends BaseAlgorithmTest
{
    protected function makeAlgorithm(
        InitializationSchemeInterface $initScheme
    ): AlgorithmInterface {
        return new Algorithm($initScheme);
    }

    /**
     * @return array<string, ScenarioData>
     */
    public function fitDataProvider(): array
    {
        return [
            'French cities' => $this->makeScenarioData(
                new Space(),
                [
                    [48.85889, 2.32004], // Paris
                    [45.75781, 4.83201], // Lyon
                    [43.29617, 5.36995], // Marseille
                ],
                10e3, // 10km radius
                10, // points per cluster
            ),
        ];
    }

    /**
     * @param array{0: float, 1: float} $center
     * @return array{0: float, 1: float}
     */
    protected function random(array $center, float $radius): array
    {
        //about 111300 meters in one degree
        $rd = $radius / 111300;

        $u = mt_rand() / mt_getrandmax();
        $v = mt_rand() / mt_getrandmax();

        $w = $rd * sqrt($u);
        $t = 2 * pi() * $v;
        $x = $w * cos($t);
        $y = $w * sin($t);

        return [$y + $center[0], $x + $center[1]];
    }

    public function testGetDistanceBetweenWithInvalidPoints(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^GPS algorithm can only calculate distance from GPS locations/');

        /** @var InitializationSchemeInterface */
        $initScheme = Mockery::mock(InitializationSchemeInterface::class);

        $algorithm = new Algorithm($initScheme);
        $algorithm->getDistanceBetween(
            new EuclideanPoint(new EuclideanSpace(2), [0, 1]),
            new EuclideanPoint(new EuclideanSpace(2), [1, 0])
        );
    }

    public function testGetCentroiWithInvalidPoins(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Point collection should consist of GPS coordinates/');

        /** @var InitializationSchemeInterface */
        $initScheme = Mockery::mock(InitializationSchemeInterface::class);

        $algorithm = new Algorithm($initScheme);
        $algorithm->findCentroid(new PointCollection(new EuclideanSpace(2), [
            new EuclideanPoint(new EuclideanSpace(2), [0, 1]),
            new EuclideanPoint(new EuclideanSpace(2), [1, 0])
        ]));
    }
}
