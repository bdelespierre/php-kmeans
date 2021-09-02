<?php

namespace Tests\Unit;

use Kmeans\Cluster;
use Kmeans\Point;
use Kmeans\PointCollection;
use Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\Cluster
 * @uses \Kmeans\Space
 * @uses \Kmeans\Point
 * @uses \Kmeans\PointCollection
 */
class ClusterTest extends TestCase
{
    private Space $space;
    /** @var array<Point> */
    private array $pointsArray;
    private Point $centroid;
    private PointCollection $points;
    private Cluster $cluster;

    public function setUp(): void
    {
        $this->space = new Space(2);

        $this->pointsArray = array_map(
            fn ($i) => new Point($this->space, [$i, $i]),
            range(1, 10)
        );

        $this->points = new PointCollection(
            $this->space,
            $this->pointsArray
        );

        $this->centroid = new Point($this->space, [0, 0]);

        $this->cluster = new Cluster(
            $this->centroid,
            $this->points
        );
    }

    public function tearDown(): void
    {
        unset(
            $this->space,
            $this->pointsArray,
            $this->points,
            $this->centroid,
            $this->cluster,
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getSpace
     * @covers ::setCentroid
     * @covers ::belongsTo
     */
    public function testBelongsTo(): void
    {
        $this->assertTrue(
            $this->cluster->belongsTo($this->space)
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getSpace
     * @covers ::setCentroid
     * @covers ::getCentroid
     */
    public function testGetCentroid(): void
    {
        $this->assertSame(
            $this->centroid,
            $this->cluster->getCentroid()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getSpace
     * @covers ::setCentroid
     * @covers ::getCentroid
     */
    public function testSetCentroid(): void
    {
        $this->cluster->setCentroid(
            $centroid = new Point($this->space, [1, 1])
        );

        $this->assertSame(
            $centroid,
            $this->cluster->getCentroid()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getSpace
     * @covers ::setCentroid
     * @covers ::getCentroid
     */
    public function testSetCentroidFailsWithInvalidCentroid(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageMatches('/^Cannot set centroid/');

        $this->cluster->setCentroid(
            new Point(new Space(3), [2, 2, 2])
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getSpace
     * @covers ::setCentroid
     * @covers ::getPoints
     */
    public function testGetPoints(): void
    {
        $this->assertCount(10, $this->cluster->getPoints());
    }

    /**
     * @covers ::__construct
     * @covers ::getSpace
     * @covers ::setCentroid
     * @covers ::attach
     * @covers ::getPoints
     */
    public function testAttach(): void
    {
        $this->cluster->attach(
            new Point($this->space, [11, 11])
        );

        $this->assertCount(11, $this->cluster->getPoints());
    }

    /**
     * @covers ::__construct
     * @covers ::getSpace
     * @covers ::setCentroid
     * @covers ::detach
     * @covers ::getPoints
     */
    public function testDetach(): void
    {
        $this->cluster->detach(
            $this->pointsArray[array_rand($this->pointsArray)]
        );

        $this->assertCount(9, $this->cluster->getPoints());
    }
}
