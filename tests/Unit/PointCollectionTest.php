<?php

namespace Tests\Unit;

use Bdelespierre\Kmeans\Interfaces\PointCollectionInterface;
use Bdelespierre\Kmeans\Interfaces\PointInterface;
use Bdelespierre\Kmeans\Point;
use Bdelespierre\Kmeans\PointCollection;
use Bdelespierre\Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Bdelespierre\Kmeans\PointCollection
 * @uses Bdelespierre\Kmeans\Space
 * @uses Bdelespierre\Kmeans\Point
 */
class PointCollectionTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::has
     * @covers ::remove
     */
    public function testAddingPointsToCollection()
    {
        $space = new Space(4);
        $collection = new PointCollection($space);

        $pointA = new Point($space, [1,2,3,4]);
        $pointB = new Point($space, [5,6,7,8]);
        $pointC = new Point($space, [9,0,1,2]);

        $collection->add($pointA);
        $collection->add($pointC);

        $this->assertTrue($collection->has($pointA));
        $this->assertFalse($collection->has($pointB));
        $this->assertTrue($collection->has($pointC));

        $collection->remove($pointC);
        $this->assertFalse($collection->has($pointC));
    }

    /**
     * @covers ::__construct
     * @covers ::add
     */
    public function testAddPointFails()
    {
        $this->expectException(\InvalidArgumentException::class);

        $spaceA = new Space(2);
        $spaceB = new Space(3);

        $collection = new PointCollection($spaceA);
        $point = new Point($spaceB, [1, 2, 3]);

        $collection->add($point);
    }

    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::remove
     * @covers ::count
     */
    public function testCount()
    {
        $space = new Space(4);
        $collection = new PointCollection($space);

        $pointA = new Point($space, [1,2,3,4]);
        $pointB = new Point($space, [5,6,7,8]);
        $pointC = new Point($space, [9,0,1,2]);

        $collection->add($pointA);
        $collection->add($pointB);
        $collection->add($pointC);

        $this->assertEquals(3, count($collection));

        $collection->remove($pointA);
        $this->assertEquals(2, count($collection));

        $collection->remove($pointB);
        $this->assertEquals(1, count($collection));

        $collection->remove($pointC);
        $this->assertEquals(0, count($collection));
    }

    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     */
    public function testIterator()
    {
        $space = new Space(4);
        $collection = new PointCollection($space);

        $pointA = new Point($space, [1,2,3,4]);
        $pointB = new Point($space, [5,6,7,8]);
        $pointC = new Point($space, [9,0,1,2]);

        $collection->add($pointA);
        $collection->add($pointB);
        $collection->add($pointC);

        $iterations = 0;
        foreach ($collection as $i => $point) {
            $this->assertInstanceof(PointInterface::class, $point);
            $iterations++;
        }

        $this->assertEquals(3, $iterations);
    }
}
