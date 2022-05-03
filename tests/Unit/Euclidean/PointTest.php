<?php

namespace Tests\Unit\Euclidean;

use Kmeans\Euclidean\Point;
use Kmeans\Euclidean\Space;
use Kmeans\Gps\Space as GpsSpace;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\Euclidean\Point
 * @uses \Kmeans\Concerns\HasSpaceTrait
 * @uses \Kmeans\Euclidean\Space
 * @uses \Kmeans\Gps\Space
 */
class PointTest extends TestCase
{
    public function testInvalidSpace(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("An euclidean point must belong to an euclidean space");

        $space = new GpsSpace();
        $point = new Point($space, [48.85889, 2.32004]);
    }

    public function testCoordinates(): void
    {
        $space = new Space(2);
        $point = new Point($space, [1.2, 3.4]);

        $this->assertSame([1.2, 3.4], $point->getCoordinates());
    }

    public function testInvalidCoordinates(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Invalid set of coordinates: 3 coordinates expected, 2 given");

        $space = new Space(3); // 3d space
        $point = new Point($space, [0.0, 0.0]); // 2d space point
    }

    public function testInvalidCoordinatesValues(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("values at offsets [0,2] could not be converted to numbers");

        $space = new Space(3); // 3d space
        $point = new Point($space, [NAN, 1.0, "hello!"]); /** @phpstan-ignore-line */
    }

    public function testAssociateData(): void
    {
        $space = new Space(2);
        $point = new Point($space, [0.0, 0.0]);

        $data = (object) ['foo' => "bar"];
        $point->setData($data);

        $this->assertSame($data, $point->getData());
    }
}
