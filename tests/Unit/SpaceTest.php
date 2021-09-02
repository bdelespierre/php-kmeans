<?php

namespace Tests\Unit;

use Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\Space
 */
class SpaceTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getDimensions
     */
    public function testGetDimensions(): void
    {
        $space = new Space(1);

        $this->assertEquals(1, $space->getDimensions());

        $space = new Space(2);

        $this->assertEquals(2, $space->getDimensions());

        $space = new Space(3);

        $this->assertEquals(3, $space->getDimensions());
    }

    /**
     * @covers ::__construct
     */
    public function testInvalidSpaceDimensions(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $space = new Space(0);
    }

    /**
     * @covers ::__construct
     * @covers ::isEqualTo
     * @covers ::getDimensions
     */
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
