<?php

namespace Tests\Unit;

use Kmeans\Interfaces\InitializationSchemeInterface;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\SpaceInterface;
use Kmeans\Point;
use Kmeans\PointCollection;
use Kmeans\RandomInitialization;
use Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\RandomInitialization
 * @uses \Kmeans\Space
 * @uses \Kmeans\Point
 * @uses \Kmeans\PointCollection
 * @uses \Kmeans\Cluster
 * @uses \Kmeans\ClusterCollection
 */
class RandomInitializationTest extends TestCase
{
    private SpaceInterface $space;
    private PointCollectionInterface $points;
    private InitializationSchemeInterface $scheme;

    public function setUp(): void
    {
        $this->space = new Space(2);

        $this->points = new PointCollection($this->space, array_map(
            fn ($coordinates) => new Point($this->space, $coordinates),
            [[0,0], [1,1], [2,2], [3,3], [4,4], [5,5], [6,6], [7,7], [8,8], [9,9]],
        ));

        $this->scheme = new RandomInitialization();
    }

    public function tearDown(): void
    {
        unset(
            $this->space,
            $this->points,
            $this->scheme
        );
    }

    /**
     * @covers ::initializeClusters
     * @covers ::getRandomPoint
     */
    public function testInitializeClusters(): void
    {
        $clusters = $this->scheme->initializeClusters($this->points, 3);

        $this->assertCount(3, $clusters);

        $expectedNbPoints = [10, 0, 0];

        foreach ($clusters as $i => $cluster) {
            $this->assertCount(
                array_shift($expectedNbPoints),
                $cluster->getPoints()
            );

            $coordinates = $cluster->getCentroid()->getCoordinates();

            $this->assertGreaterThanOrEqual(0, $coordinates[0]);
            $this->assertGreaterThanOrEqual(0, $coordinates[1]);

            $this->assertLessThanOrEqual(9, $coordinates[0]);
            $this->assertLessThanOrEqual(9, $coordinates[1]);
        }
    }

    /**
     * @covers ::initializeClusters
     */
    public function testInitializeClustersWithInvalidClusterCount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Invalid cluster count/');

        $this->scheme->initializeClusters($this->points, 0);
    }

    /**
     * @covers ::initializeClusters
     * @covers ::getRandomPoint
     */
    public function testInitializeClustersWithoutPoints(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageMatches('/^Unable to pick a random point out of an empty point collection/');

        $this->scheme->initializeClusters(new PointCollection($this->space), 3);
    }
}
