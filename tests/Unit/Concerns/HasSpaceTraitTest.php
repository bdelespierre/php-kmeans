<?php

namespace Tests\Unit\Concerns;

use Kmeans\Concerns\HasSpaceTrait;
use Kmeans\Euclidean\Space;
use Kmeans\Interfaces\SpaceBoundInterface;
use Kmeans\Interfaces\SpaceInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\Concerns\HasSpaceTrait
 * @uses \Kmeans\Euclidean\Space
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

    public function testGetSpace(): void
    {
        $this->assertSame($this->space, $this->point->getSpace());
    }

    public function testBelongsTo(): void
    {
        $this->assertTrue($this->point->belongsTo($this->space));
        $this->assertTrue($this->point->belongsTo(new Space(2)));
        $this->assertFalse($this->point->belongsTo(new Space(3)));
    }
}
