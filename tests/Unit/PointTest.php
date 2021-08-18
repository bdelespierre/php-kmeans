<?php

namespace Tests\Unit;

use Bdelespierre\Kmeans\Point;
use Bdelespierre\Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Bdelespierre\Kmeans\Point
 * @uses Bdelespierre\Kmeans\Space
 */
class PointTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::setCoordinates
     * @covers ::getCoordinates
     */
    public function testCoordinates()
    {
        $space = new Space(2);
        $point = new Point($space, [0.0, 0.0]);

        $this->assertSame([0.0, 0.0], $point->getCoordinates());

        $point->setCoordinates([1.2, 3.4]);

        $this->assertSame([1.2, 3.4], $point->getCoordinates());
    }

    /**
     * @covers ::__construct
     * @covers ::setCoordinates
     */
    public function testInvalidCoordinates()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Invalid set of coordinates: 3 coordinates expected, 2 coordinates given");

        $space = new Space(3); // 3d space
        $point = new Point($space, [0.0, 0.0]); // 2d space point
    }

    /**
     * @covers ::__construct
     * @covers ::setCoordinates
     */
    public function testInvalidCoordinatesValues()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("values at offsets [0,2] could not be converted to numbers");

        $space = new Space(3); // 3d space
        $point = new Point($space, [NAN, 1.0, "hello!"]);
    }

    /**
     * @covers ::__construct
     * @covers ::setCoordinates
     * @covers ::getData
     * @covers ::setData
     */
    public function testAssociateData()
    {
        $space = new Space(2);
        $point = new Point($space, [0.0, 0.0]);

        $data = (object) ['foo' => "bar"];

        $point->setData($data);

        $this->assertSame($data, $point->getData());
    }
}
