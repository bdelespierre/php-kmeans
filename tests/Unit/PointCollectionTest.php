<?php

namespace Tests\Unit;

use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;
use Kmeans\Point;
use Kmeans\PointCollection;
use Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\PointCollection
 * @uses \Kmeans\Space
 * @uses \Kmeans\Point
 */
class PointCollectionTest extends TestCase
{
    private Space $space;
    /** @var array<Point> */
    private array $pointsArray;
    private PointCollection $points;

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
    }

    public function tearDown(): void
    {
        unset(
            $this->space,
            $this->pointsArray,
            $this->points,
        );
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::count
     */
    public function testAttach(): void
    {
        $this->points->attach(
            new Point($this->space, [11, 11])
        );

        $this->assertCount(11, $this->points);
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::count
     */
    public function testAttachTwiceHasNoEffect(): void
    {
        $this->points->attach(
            $point = new Point($this->space, [11, 11])
        );

        $this->points->attach($point);

        $this->assertCount(11, $this->points);
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::count
     */
    public function testAttachInvalidPointFails(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Cannot add point to collection/');

        $this->points->attach(
            $point = new Point(new Space(3), [11, 11, 11])
        );

        $this->points->attach($point);

        $this->assertCount(11, $this->points);
    }

    /**
     * @covers ::__construct
     * @covers ::contains
     * @covers ::attach
     */
    public function testContains(): void
    {
        $this->assertTrue(
            $this->points->contains(
                $this->pointsArray[array_rand($this->pointsArray)]
            )
        );

        $this->assertFalse(
            $this->points->contains(
                new Point($this->space, [11, 11])
            )
        );
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::detach
     * @covers ::count
     */
    public function testDetach(): void
    {
        $this->points->detach(
            $this->pointsArray[array_rand($this->pointsArray)]
        );

        $this->assertCount(9, $this->points);
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::detach
     * @covers ::count
     */
    public function testDetachTwiceHasNoEffect(): void
    {
        $this->points->detach(
            $point = $this->pointsArray[array_rand($this->pointsArray)]
        );

        $this->points->detach($point);

        $this->assertCount(9, $this->points);
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     */
    public function testIteration(): void
    {
        foreach ($this->points as $key => $point) {
            $this->assertTrue(
                array_search($point, $this->pointsArray, true) !== false
            );
        }
    }
}
