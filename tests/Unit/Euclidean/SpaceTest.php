<?php

namespace Tests\Unit\Euclidean;

use Kmeans\Euclidean\Space;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\Euclidean\Space
 */
class SpaceTest extends TestCase
{
    public function testGetDimensions(): void
    {
        $space = new Space(1);

        $this->assertEquals(1, $space->getDimensions());

        $space = new Space(2);

        $this->assertEquals(2, $space->getDimensions());

        $space = new Space(3);

        $this->assertEquals(3, $space->getDimensions());
    }

    public function testInvalidSpaceDimensions(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $space = new Space(0);
    }

    public function testIsEqualTo(): void
    {
        $this->assertTrue(
            (new Space(1))->isEqualTo(new Space(1))
        );

        $this->assertFalse(
            (new Space(1))->isEqualTo(new Space(2))
        );
    }
}
