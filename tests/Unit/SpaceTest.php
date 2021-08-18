<?php

namespace Tests\Unit;

use Bdelespierre\Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Bdelespierre\Kmeans\Space
 */
class SpaceTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getDimensions
     */
    public function testGetDimensions()
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
    public function testInvalidSpaceDimensions()
    {
        $this->expectException(\LogicException::class);

        $space = new Space(0);
    }
}
