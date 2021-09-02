<?php

namespace Tests\Unit\Concerns;

use Kmeans\Concerns\HasSpaceTrait;
use Kmeans\Interfaces\SpaceBoundInterface;
use Kmeans\Interfaces\SpaceInterface;
use Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\Concerns\HasSpaceTrait
 * @uses \Kmeans\Space
 */
class HasSpaceTraitTest extends TestCase
{
    private Space $space;
    private SpaceBoundInterface $point;

    public function setUp(): void
    {
        $this->space = new Space(2);

        $this->point = new class ($this->space) implements SpaceBoundInterface {
            use HasSpaceTrait;

            public function __construct(SpaceInterface $space)
            {
                $this->setSpace($space);
            }
        };
    }

    /**
     * @covers ::setSpace
     * @covers ::getSpace
     */
    public function testGetSpace(): void
    {
        $this->assertSame($this->space, $this->point->getSpace());
    }

    /**
     * @covers ::setSpace
     * @covers ::getSpace
     * @covers ::belongsTo
     */
    public function testBelongsTo(): void
    {
        $this->assertTrue($this->point->belongsTo($this->space));
        $this->assertTrue($this->point->belongsTo(new Space(2)));
        $this->assertFalse($this->point->belongsTo(new Space(3)));
    }
}
