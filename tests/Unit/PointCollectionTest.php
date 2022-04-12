<?php

namespace Tests\Unit;

use Kmeans\Euclidean\Point;
use Kmeans\Euclidean\Space;
use Kmeans\Interfaces\PointCollectionInterface;
use Kmeans\Interfaces\PointInterface;
use Kmeans\PointCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\PointCollection
 * @uses \Kmeans\Euclidean\Point
 * @uses \Kmeans\Euclidean\Space
 */
class PointCollectionTest extends TestCase
{
    public static function makePointCollection(): PointCollection
    {
        $space = new Space(2);

        return new PointCollection(
            $space,
            [
                new Point($space, [1,1]),
                new Point($space, [2,2]),
                new Point($space, [3,3]),
                new Point($space, [4,4]),
                new Point($space, [5,5]),
            ]
        );
    }

    public function testAttach(): void
    {
        $points = self::makePointCollection();

        $points->attach(
            new Point(new Space(2), [6,6])
        );

        $this->assertCount(6, $points);
    }

    public function testAttachTwiceHasNoEffect(): void
    {
        $points = self::makePointCollection();

        $points->attach(
            $point = new Point(new Space(2), [6,6])
        );

        $points->attach($point);

        $this->assertCount(6, $points);
    }

    public function testAttachInvalidPointFails(): void
    {
        $points = self::makePointCollection();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Cannot add point to collection/');

        $points->attach(
            $point = new Point(new Space(3), [6,6,6])
        );

        $points->attach($point);

        $this->assertCount(11, $points);
    }

    public function testContains(): void
    {
        $points = self::makePointCollection();
        $arr = iterator_to_array($points);
        $point = $arr[array_rand($arr)];

        $this->assertTrue(
            $points->contains($point)
        );

        $this->assertFalse(
            $points->contains(
                new Point(new Space(2), [6,6])
            )
        );
    }

    public function testDetach(): void
    {
        $points = self::makePointCollection();
        $arr = iterator_to_array($points);
        $point = $arr[array_rand($arr)];

        $points->detach($point);

        $this->assertCount(4, $points);
    }

    public function testDetachTwiceHasNoEffect(): void
    {
        $points = self::makePointCollection();
        $arr = iterator_to_array($points);
        $point = $arr[array_rand($arr)];

        $points->detach($point);
        $points->detach($point);

        $this->assertCount(4, $points);
    }

    public function testIteration(): void
    {
        $points = self::makePointCollection();

        foreach ($points as $key => $point) {
            $this->assertInstanceof(PointInterface::class, $point);
        }
    }
}
