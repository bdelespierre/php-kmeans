<?php

namespace Tests\Unit\Gps;

use Kmeans\Gps\Point;
use Kmeans\Gps\Space;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\Gps\Point
 * @uses \Kmeans\Gps\Space
 */
class PointTest extends TestCase
{
    public function testConstruct(): Point
    {
        $point = new Point(48.85889, 2.32004);

        $this->assertTrue(
            $point->getSpace()->isEqualTo(new Space())
        );

        return $point;
    }

    /**
     * @depends testConstruct
     */
    public function testGetCoordinates(Point $point): void
    {
        $this->assertEquals(
            [48.85889, 2.32004],
            $point->getCoordinates(),
        );
    }

    /**
     * @dataProvider invalidGpsCoordinatesDataProvider
     */
    public function testConstructWithInvalidCoordinates(float $lat, float $long): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid GPS coordinates");

        $point = new Point($lat, $long);
    }

    /**
     * @return array<string, array{0: float, 1: float}>
     */
    public function invalidGpsCoordinatesDataProvider(): array
    {
        return [
            'invalid lat (-91)' => [-91,0],
            'invalid lat (91)' => [91,0],
            'invalid long (-181)' => [-181,0],
            'invalid long (181)' => [181,0],
        ];
    }
}
