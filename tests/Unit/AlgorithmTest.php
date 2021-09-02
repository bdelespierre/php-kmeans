<?php

namespace Tests\Unit;

use Kmeans\Algorithm;
use Kmeans\Cluster;
use Kmeans\ClusterCollection;
use Kmeans\Interfaces\AlgorithmInterface;
use Kmeans\Interfaces\ClusterCollectionInterface;
use Kmeans\Interfaces\InitializationSchemeInterface;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\SpaceInterface;
use Kmeans\Point;
use Kmeans\PointCollection;
use Kmeans\Space;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\Algorithm
 * @uses \Kmeans\Cluster
 * @uses \Kmeans\ClusterCollection
 * @uses \Kmeans\Point
 * @uses \Kmeans\PointCollection
 * @uses \Kmeans\Space
 */
class AlgorithmTest extends TestCase
{
    private const MT_RAND_SEED = 123456;

    public static function setUpBeforeClass(): void
    {
        mt_srand(self::MT_RAND_SEED);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @dataProvider clusterizeDataProvider
     * @covers ::__construct
     * @covers ::clusterize
     * @covers ::findCentroid
     * @covers ::getClosestCluster
     * @covers ::getDistanceBetween
     * @covers ::invokeIterationCallbacks
     * @covers ::iterate
     * @covers euclidean_dist
     * @covers find_centroid
     * @covers generate_gaussian_noise
     * @param int<0, max> $dimensions
     * @param array<array<float>> $expected
     * @param array<array<float>> $initialClusterCentroids
     * @param int<0, max> $nbPointsPerCentroid
     */
    public function testClusterize(
        int $dimensions,
        array $expected,
        array $initialClusterCentroids,
        int $nbPointsPerCentroid
    ): void {
        $space = new Space($dimensions);
        $radius = 1;

        $points = $this->makePointsAround(
            $space,
            $expected,
            $radius,
            $nbPointsPerCentroid,
        );

        $clusters = $this->makeClusters(
            $points,
            $initialClusterCentroids
        );

        $algo = new Algorithm(
            $this->mockInitScheme($clusters)
        );

        $resultClusters = iterator_to_array(
            $algo->clusterize($points, count($expected))
        );

        foreach ($expected as $n => $expectedCentroid) {
            // assert found cluster centroids are in the vicinity
            // of expected centroids
            $this->assertLessThan(1, euclidean_dist(
                $expectedCentroid,
                $resultClusters[$n]->getCentroid()->getCoordinates()
            ));

            // assert found cluster has $nbPoints points attached
            $this->assertCount(
                $nbPointsPerCentroid,
                $resultClusters[$n]->getPoints()
            );
        }
    }

    /**
     * @return array<mixed>
     */
    public function clusterizeDataProvider(): array
    {
        return [
            'one dimension, 3 clusters, 5 points per cluster' => [
                'dimension' => 1,
                'expected' => [
                    [-50],
                    [0],
                    [50],
                ],
                'initialClusterCentroids' => [
                    [-10],
                    [0],
                    [10]
                ],
                'nbPointsPerCentroid' => 5,
            ],

            'two dimensions, 3 clusters, 50 points per cluster' => [
                'dimension' => 2,
                'expected' => [
                    [20, 10],
                    [40, 20],
                    [60, 15],
                ],
                'initialClusterCentroids' => [
                    [12, 10],
                    [33, 20],
                    [60, 10],
                ],
                'nbPointsPerCentroid' => 50,
            ],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::clusterize
     */
    public function testClusterizeFailsWhenClusterInitializationFails(): void
    {
        /** @var InitializationSchemeInterface */
        $initScheme = Mockery::mock(InitializationSchemeInterface::class);

        /** @phpstan-ignore-next-line */
        $initScheme
            ->shouldReceive('initializeClusters')
            ->with(PointCollectionInterface::class, Mockery::type('integer'))
            ->andThrow(new \Exception('n/a'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Cannot initialize clusters");

        (new Algorithm($initScheme))->clusterize(new PointCollection(new Space(1)), 1);
    }

    /**
     * @covers ::__construct
     * @covers ::registerIterationCallback
     * @covers ::clusterize
     * @covers ::iterate
     * @covers ::invokeIterationCallbacks
     * @covers ::getClosestCluster
     * @covers ::getDistanceBetween
     * @covers euclidean_dist
     */
    public function testIterationCallback(): void
    {
        $space = new Space(1);

        $points = new PointCollection($space, array_map(
            fn ($coordinates) => new Point($space, $coordinates),
            [[1],[2],[3],[4],[5]]
        ));

        $clusters = new ClusterCollection($space, [
            new Cluster(new Point($space, [6]), $points)
        ]);

        $callbackCalled = false;

        $algo = new Algorithm($this->mockInitScheme($clusters));
        $algo->registerIterationCallback(
            function (AlgorithmInterface $algo, ClusterCollectionInterface $cluster) use (&$callbackCalled) {
                $callbackCalled = true;
            }
        );

        $algo->clusterize($points, 1);

        $this->assertTrue($callbackCalled);
    }

    /**
     * @param array<array<float>> $centroids
     * @param int<0, max> $nbPointsPerCentroid
     */
    private function makePointsAround(
        SpaceInterface $space,
        array $centroids,
        float $radius,
        int $nbPointsPerCentroid
    ): PointCollectionInterface {
        $points = new PointCollection($space);

        foreach ($centroids as $centroid) {
            for ($i = 0; $i < $nbPointsPerCentroid; $i++) {
                $coordinates = $centroid;

                foreach ($coordinates as &$n) {
                    list($n) = generate_gaussian_noise($n, $radius);
                }

                $points->attach(new Point($space, $coordinates));
            }
        }

        return $points;
    }

    /**
     * @param array<array<float>> $centroids
     */
    private function makeClusters(PointCollectionInterface $points, array $centroids): ClusterCollectionInterface
    {
        $clusters = new ClusterCollection($points->getSpace());

        foreach ($centroids as $n => $centroid) {
            $clusters->attach(new Cluster(
                new Point($points->getSpace(), $centroid),
                $n == 0 ? $points : null
            ));
        }

        return $clusters;
    }

    private function mockInitScheme(ClusterCollectionInterface $clusters): InitializationSchemeInterface
    {
        /** @var InitializationSchemeInterface */
        $initScheme = Mockery::mock(InitializationSchemeInterface::class);

        /** @phpstan-ignore-next-line */
        $initScheme
            ->shouldReceive('initializeClusters')
            ->with(PointCollectionInterface::class, Mockery::type('integer'))
            ->andReturn($clusters);

        return $initScheme;
    }
}
