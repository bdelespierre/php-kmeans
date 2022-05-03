<?php

namespace Tests\Unit;

use Kmeans\Cluster;
use Kmeans\ClusterCollection;
use Kmeans\Interfaces\AlgorithmInterface;
use Kmeans\Interfaces\ClusterCollectionInterface;
use Kmeans\Interfaces\InitializationSchemeInterface;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Interfaces\SpaceInterface;
use Kmeans\PointCollection;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\Algorithm
 * @uses \Kmeans\Cluster
 * @uses \Kmeans\ClusterCollection
 * @phpstan-type ClusterizeScenarioData array{
 *     space: SpaceInterface,
 *     radius: float,
 *     points: PointCollectionInterface,
 *     initialCentroids: PointCollectionInterface,
 *     expectedCentroids: PointCollectionInterface,
 * }
 */
abstract class AlgorithmTest extends TestCase
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

    // ------------------------------------------------------------------------
    // abstract

    abstract protected function makeAlgorithm(InitializationSchemeInterface $initScheme): AlgorithmInterface;

    /**
     * @return array<string, ClusterizeScenarioData>
     */
    abstract public function clusterizeDataProvider(): array;

    /**
     * @param array<float> $center
     * @return array<float>
     */
    abstract protected function random(array $center, float $radius): array;

    // ------------------------------------------------------------------------
    // tests

    /**
     * @dataProvider clusterizeDataProvider
     */
    public function testClusterize(
        SpaceInterface $space,
        float $radius,
        PointCollectionInterface $points,
        PointCollectionInterface $initialCentroids,
        PointCollectionInterface $expectedCentroids,
    ): void {
        $algorithm = $this->makeAlgorithm(
            $this->mockInitScheme(
                $this->makeClusters($points, $initialCentroids)
            )
        );

        $result = iterator_to_array(
            $algorithm->clusterize($points, count($expectedCentroids))
        );

        foreach ($expectedCentroids as $i => $expectedCentroid) {
            $this->assertLessThan(
                $radius,
                $algorithm->getDistanceBetween(
                    $expectedCentroid,
                    $result[$i]->getCentroid()
                )
            );

            if (
                is_array($expectedCentroid->getData())
                && isset($expectedCentroid->getData()['count'])
            ) {
                $this->assertCount(
                    $expectedCentroid->getData()['count'],
                    $result[$i]->getPoints()
                );
            }
        }
    }

    // ------------------------------------------------------------------------
    // helpers

    /**
     * @param array<array<float>> $centers
     * @return ClusterizeScenarioData
     */
    protected function makeClusterizeScenarioData(
        SpaceInterface $space,
        array $centers,
        float $radius,
        int $count
    ): array {
        $points = new PointCollection($space);
        for ($i = 0; $i < count($centers); $i++) {
            for ($j = 0; $j < $count; $j++) {
                $point = $space->makePoint($this->random($centers[$i], $radius));
                $points->attach($point);
            }
        }

        $initialCentroids = new PointCollection($space);
        for ($i = 0; $i < count($centers); $i++) {
            $point = $space->makePoint($centers[$i]);
            $initialCentroids->attach($point);
        }

        $expectedCentroids = new PointCollection($space);
        for ($i = 0; $i < count($centers); $i++) {
            $point = $space->makePoint($centers[$i]);
            $point->setData(['count' => $count]);
            $expectedCentroids->attach($point);
        }

        return compact(
            'space',
            'radius',
            'points',
            'initialCentroids',
            'expectedCentroids'
        );
    }

    protected function makeClusters(
        PointCollectionInterface $points,
        PointCollectionInterface $centroids
    ): ClusterCollectionInterface {
        $clusters = new ClusterCollection($points->getSpace());

        foreach ($centroids as $n => $centroid) {
            // attach all points to the first cluster
            $clusters->attach(new Cluster($centroid, $n == 0 ? $points : null));
        }

        return $clusters;
    }

    protected function mockInitScheme(
        ClusterCollectionInterface $clusters
    ): InitializationSchemeInterface {
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
