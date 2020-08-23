<?php

namespace Tests\Kmeans;

use KMeans\Point;
use KMeans\Space;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    public function testConstruct()
    {
        $space = new Space(2);
        $point = new Point($space, [0,0]);

        $this->assertInstanceOf(Point::class, $point);
    }

    public function testToArray()
    {
        $space = new Space(2);
        $point = new Point($space, [0,0]);

        $this->assertEquals(['coordinates' => [0,0], 'data' => null], $point->toArray());

        $space[$point] = "foobar";

        $this->assertEquals(['coordinates' => [0,0], 'data' => "foobar"], $point->toArray());
    }

    public function testGetDistanceWith()
    {
        $space  = new Space(2);
        $point1 = new Point($space, [1,1]);
        $point2 = new Point($space, [2,1]);

        $this->assertEquals(1, $point1->getDistanceWith($point2));
    }

    public function testGetDistanceWithException()
    {
        $this->expectException(\LogicException::class);

        $space  = new Space(2);
        $point1 = new Point($space, [1,1]);

        $space  = new Space(3);
        $point2 = new Point($space, [2,1,0]);

        $point1->getDistanceWith($point2);
    }

    public function testGetClosest()
    {
        $space  = new Space(2);
        $points = [
            new Point($space, [-2,-2]),
            new Point($space, [-1,-1]),
            new Point($space, [ 0, 0]),
            new Point($space, [ 1, 1]),
            new Point($space, [ 2, 2]),
        ];

        $this->assertEquals($points[0], (new Point($space, [-2.1, -2.1]))->getClosest($points));
        $this->assertEquals($points[1], (new Point($space, [-1.1, -1.1]))->getClosest($points));
        $this->assertEquals($points[2], (new Point($space, [ 0.1,  0.1]))->getClosest($points));
        $this->assertEquals($points[3], (new Point($space, [ 1.1,  1.1]))->getClosest($points));
        $this->assertEquals($points[4], (new Point($space, [ 2.1,  2.1]))->getClosest($points));
    }

    public function testBelongsTo()
    {
        $space = new Space(2);
        $point = new Point($space, [0,0]);

        $this->assertTrue($point->belongsTo($space));
        $this->assertFalse($point->belongsTo(new Space(2)));
    }

    public function testGetSpace()
    {
        $space = new Space(2);
        $point = new Point($space, [0,0]);

        $this->assertTrue($point->getSpace() === $space);
    }

    public function testGetCoordinates()
    {
        $space = new Space(2);
        $point = new Point($space, [0,0]);

        $this->assertEquals([0,0], $point->getCoordinates());
    }

    public function testOffsetExists()
    {
        $space = new Space(2);
        $point = new Point($space, [0,0]);

        $this->assertTrue($point->offsetExists(0));
        $this->assertTrue($point->offsetExists(1));
        $this->assertFalse($point->offsetExists(2));
    }

    public function testOffsetGet()
    {
        $space = new Space(2);
        $point = new Point($space, [1,2]);

        $this->assertEquals(1, $point->offsetGet(0));
        $this->assertEquals(2, $point->offsetGet(1));
    }

    public function testOffsetSet()
    {
        $space = new Space(2);
        $point = new Point($space, [1,2]);

        $point->offsetSet(0, 3);
        $point->offsetSet(1, 4);

        $this->assertEquals(3, $point->offsetGet(0));
        $this->assertEquals(4, $point->offsetGet(1));
    }

    public function testOffsetUnset()
    {
        $space = new Space(2);
        $point = new Point($space, [1,2]);

        $point->offsetUnset(0);
        $point->offsetUnset(1);

        $this->assertFalse($point->offsetExists(0));
        $this->assertFalse($point->offsetExists(1));
    }
}
