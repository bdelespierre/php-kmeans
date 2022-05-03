<?php

namespace Tests\Unit\Gps;

use Kmeans\Euclidean\Space as EuclideanSpace;
use Kmeans\Gps\Point;
use Kmeans\Gps\Space;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\Gps\Space
 * @uses \Kmeans\Euclidean\Space
 * @uses \Kmeans\Gps\Point
 */
class SpaceTest extends TestCase
{
    public function testSingleton(): void
    {
        $this->assertInstanceof(
            Space::class,
            Space::singleton(),
        );

        $this->assertSame(
            Space::singleton(),
            Space::singleton(),
        );
    }

    public function testIsEqualTo(): void
    {
        $this->assertTrue(
            (new Space())->isEqualTo(new Space())
        );

        $this->assertFalse(
            (new Space())->isEqualTo(new EuclideanSpace(1))
        );
    }

    public function testMakePoint(): void
    {
        $this->assertInstanceof(
            Point::class,
            (new Space())->makePoint([48.85889, 2.32004])
        );
    }
}
