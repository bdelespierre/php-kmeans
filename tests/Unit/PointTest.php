<?php

namespace Tests\Unit;

use Kmeans\Point;
use Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\Point
 * @uses \Kmeans\Space
 * @uses \Kmeans\Concerns\HasSpaceTrait
 */
class PointTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::sanitizeCoordinates
     * @covers ::getCoordinates
     */
    public function testCoordinates(): void
    {
        $space = new Space(2);
        $point = new Point($space, [1.2, 3.4]);

        $this->assertSame([1.2, 3.4], $point->getCoordinates());
    }

    /**
     * @covers ::__construct
     * @covers ::sanitizeCoordinates
     */
    public function testInvalidCoordinates(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Invalid set of coordinates: 3 coordinates expected, 2 given");

        $space = new Space(3); // 3d space
        $point = new Point($space, [0.0, 0.0]); // 2d space point
    }

    /**
     * @covers ::__construct
     * @covers ::sanitizeCoordinates
     */
    public function testInvalidCoordinatesValues(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("values at offsets [0,2] could not be converted to numbers");

        $space = new Space(3); // 3d space
        $point = new Point($space, [NAN, 1.0, "hello!"]); /** @phpstan-ignore-line */
    }

    /**
     * @covers ::__construct
     * @covers ::sanitizeCoordinates
     * @covers ::getData
     * @covers ::setData
     */
    public function testAssociateData(): void
    {
        $space = new Space(2);
        $point = new Point($space, [0.0, 0.0]);

        $data = (object) ['foo' => "bar"];
        $point->setData($data);

        $this->assertSame($data, $point->getData());
    }
}
