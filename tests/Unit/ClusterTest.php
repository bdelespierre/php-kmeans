<?php

namespace Tests\Unit;

use Kmeans\Cluster;
use Kmeans\Euclidean\Point;
use Kmeans\Euclidean\Space;
use Kmeans\PointCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\Cluster
 * @uses \Kmeans\Euclidean\Point
 * @uses \Kmeans\Euclidean\Space
 * @uses \Kmeans\PointCollection
 */
class ClusterTest extends TestCase
{
    public static function makeCluster(): Cluster
    {
        return new Cluster(
            new Point(new Space(2), [3,3]),
            PointCollectionTest::makePointCollection()
        );
    }

    public function testBelongsTo(): void
    {
        $cluster = self::makeCluster();

        $this->assertTrue(
            $cluster->belongsTo(new Space(2))
        );
    }

    public function testGetCentroid(): void
    {
        $cluster = self::makeCluster();

        $this->assertSame(
            [3.0,3.0],
            $cluster->getCentroid()->getCoordinates()
        );
    }

    public function testSetCentroid(): void
    {
        $cluster = self::makeCluster();

        $cluster->setCentroid(
            $centroid = new Point(new Space(2), [1,1])
        );

        $this->assertSame(
            $centroid,
            $cluster->getCentroid()
        );
    }

    public function testSetCentroidFailsWithInvalidCentroid(): void
    {
        $cluster = self::makeCluster();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageMatches('/^Cannot set centroid/');

        $cluster->setCentroid(
            new Point(new Space(3), [6,6,6])
        );
    }

    public function testGetPoints(): void
    {
        $cluster = self::makeCluster();

        $this->assertCount(5, $cluster->getPoints());
    }

    public function testAttach(): Cluster
    {
        $cluster = self::makeCluster();

        $cluster->attach(
            new Point(new Space(2), [6,6])
        );

        $this->assertCount(6, $cluster->getPoints());

        return $cluster;
    }

    public function testDetach(): void
    {
        $cluster = self::makeCluster();
        $points = iterator_to_array($cluster->getPoints());
        $point = $points[array_rand($points)];

        $cluster->detach($point);

        $this->assertCount(4, $cluster->getPoints());
    }
}
