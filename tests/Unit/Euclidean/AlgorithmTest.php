<?php

namespace Tests\Unit\Euclidean;

use Kmeans\Euclidean\Algorithm;
use Kmeans\Euclidean\Point;
use Kmeans\Euclidean\Space;
use Kmeans\Interfaces\AlgorithmInterface;
use Kmeans\Interfaces\ClusterCollectionInterface;
use Kmeans\Interfaces\InitializationSchemeInterface;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\SpaceInterface;
use Kmeans\Math;
use Kmeans\PointCollection;
use Kmeans\RandomInitialization;
use Tests\Unit\AlgorithmTest as BaseAlgorithmTest;

/**
 * @covers \Kmeans\Euclidean\Algorithm
 * @covers \Kmeans\Algorithm
 * @uses \Kmeans\Cluster
 * @uses \Kmeans\ClusterCollection
 * @uses \Kmeans\Concerns\HasDataTrait
 * @uses \Kmeans\Concerns\HasSpaceTrait
 * @uses \Kmeans\Euclidean\Point
 * @uses \Kmeans\Euclidean\Space
 * @uses \Kmeans\Math
 * @uses \Kmeans\PointCollection
 * @uses \Kmeans\RandomInitialization
 * @phpstan-import-type ScenarioData from BaseAlgorithmTest
 */
class AlgorithmTest extends BaseAlgorithmTest
{
    /**
     * @dataProvider fitDataProvider
     */
    public function testIterationCallback(
        SpaceInterface $space,
        float $radius,
        PointCollectionInterface $points,
        PointCollectionInterface $initialCentroids,
        PointCollectionInterface $expectedCentroids,
    ): void {
        /** @var \Kmeans\Algorithm $algorithm */
        $algorithm = $this->makeAlgorithm(
            $this->mockInitScheme(
                $this->makeClusters($points, $initialCentroids)
            )
        );

        $called = false;
        $algorithm->registerIterationCallback(function () use (&$called) {
            $called = true;
        });

        $algorithm->fit($points, count($expectedCentroids));

        $this->assertTrue($called);
    }

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
            '1D' => $this->makeScenarioData(
                new Space(1),
                [
                    [-100],
                    [0],
                    [100]
                ],
                2, // radius
                10, // points per clusters
            ),
            '2D' => $this->makeScenarioData(
                new Space(2),
                [
                    [-100, -100],
                    [0, 0],
                    [100, 100],
                ],
                2, // radius
                10, // points per clusters
            ),
            '3D' => $this->makeScenarioData(
                new Space(3),
                [
                    [-100, -100, -100],
                    [0, 0, 0],
                    [100, 100, 100],
                ],
                2, // radius
                10, // points per clusters
            ),
        ];
    }

    /**
     * @param array<float> $center
     * @return array<float>
     */
    protected function random(array $center, float $radius): array
    {
        $point = $center;

        foreach ($point as &$c) {
            $blur = Math::gaussianNoise($c, $radius);
            $c = $blur[array_rand($blur)];
        }

        return $point;
    }

    /**
     * @uses \Kmeans\Gps\Point
     * @uses \Kmeans\Gps\Space
     */
    public function testGetDistanceBetweenException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var InitializationSchemeInterface */
        $initScheme = \Mockery::mock(InitializationSchemeInterface::class);

        $algorithm = new Algorithm($initScheme);
        $algorithm->getDistanceBetween(
            new \Kmeans\Gps\Point(0, 0),
            new \Kmeans\Gps\Point(0, 0)
        );
    }

    public function testFindCentroidException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var InitializationSchemeInterface */
        $initScheme = \Mockery::mock(InitializationSchemeInterface::class);

        $algorithm = new Algorithm($initScheme);
        $algorithm->findCentroid(
            new PointCollection(new \Kmeans\Gps\Space(), [])
        );
    }

    public function testMaxIterations(): void
    {
        $algorithm = new class (new RandomInitialization()) extends Algorithm {
            protected function iterate(ClusterCollectionInterface $clusters): bool
            {
                // do nothing and iterate indefinitely
                return true;
            }
        };

        $iterations = 0;
        $algorithm->registerIterationCallback(function () use (&$iterations) {
            $iterations++;
        });

        $space = new Space(1);
        $points = new PointCollection(
            $space,
            array_map([$space, 'makePoint'], [[1],[2],[3]])
        );

        $algorithm->fit($points, 3, 300);

        $this->assertEquals(
            300,
            $iterations
        );
    }

    public function testMaxIterationsException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessageMatches('/^Invalid maximum number of iterations/');

        $algorithm = new Algorithm(new RandomInitialization());
        $algorithm->fit(new PointCollection(new Space(1), []), 3, 0);
    }
}
