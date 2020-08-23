<?php

namespace Tests\Kmeans;

use KMeans\Point;
use KMeans\Space;
use PHPUnit\Framework\TestCase;

class SpaceTest extends TestCase
{
    public function testConstruct()
    {
        $space = new Space(2);

        $this->assertInstanceOf(Space::class, new Space(1));
        $this->assertInstanceOf(Space::class, new Space(2));
        $this->assertInstanceOf(Space::class, new Space(3));
        $this->assertInstanceOf(Space::class, new Space(50));
    }

    public function testConstructException()
    {
        $this->expectException(\LogicException::class);

        new Space(-1);
    }

    public function testToArray()
    {
        $space  = new Space(2);
        $points = [
            new Point($space, [-2,-2]),
            new Point($space, [-1,-1]),
            new Point($space, [ 0, 0]),
            new Point($space, [ 1, 1]),
            new Point($space, [ 2, 2]),
        ];

        foreach ($points as $point) {
            $space->attach($point);
        }

        $this->assertEquals(
            ['points' => array_map(fn($p) => $p->toArray(), $points)],
            $space->toArray()
        );
    }

    public function testNewPoint()
    {
        $space = new Space(2);

        $this->assertInstanceOf(Point::class, $space->newPoint([0,0]));
    }

    public function testNewPointException()
    {
        $this->expectException(\LogicException::class);

        $space = new Space(2);
        $space->newPoint([1,2,3]);
    }

    public function testAddPoint()
    {
        $space = new Space(2);

        $space->addPoint([0,0]);
        $space->addPoint([1,1]);
        $space->addPoint([2,2]);

        $this->assertCount(3, $space);
    }

    public function testAttach()
    {
        $space = new Space(2);

        $space->attach(new Point($space, [0,0]));
        $space->attach(new Point($space, [1,1]));
        $space->attach(new Point($space, [2,2]));

        $this->assertCount(3, $space);
    }

    public function testAttachException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $space = new Space(2);
        $space->attach("INVALID");
    }

    public function testGetDimention()
    {
        $this->assertEquals(1, (new Space(1))->getDimention());
        $this->assertEquals(2, (new Space(2))->getDimention());
        $this->assertEquals(3, (new Space(3))->getDimention());
    }

    public function testGetBoundaries()
    {
        $space = new Space(2);

        $this->assertEmpty($space->getBoundaries());

        $space->attach($p1 = new Point($space, [ 0, 0]));
        $space->attach($p2 = new Point($space, [ 0,10]));
        $space->attach($p3 = new Point($space, [10, 0]));
        $space->attach($p4 = new Point($space, [10,10]));

        $this->assertEquals([$p1, $p4], $space->getBoundaries());
    }

    public function testGetRandomPoint()
    {
        $space = new Space(1);

        $min = new Point($space, [0]);
        $max = new Point($space, [10]);

        Space::setRng(fn($min, $max) => $min);
        $this->assertEquals($min, $space->getRandomPoint($min, $max));

        Space::setRng(fn($min, $max) => $max);
        $this->assertEquals($max, $space->getRandomPoint($min, $max));
    }

    public function testSolve()
    {
        Space::setRng(function ($min, $max) {
            static $values = [10, 0];
            return array_pop($values) ?? mt_rand($min, $max);
        });

        $space = new Space(1);

        $space->attach($space->newPoint([1]));
        $space->attach($space->newPoint([2]));
        $space->attach($space->newPoint([3]));

        $space->attach($space->newPoint([7]));
        $space->attach($space->newPoint([8]));
        $space->attach($space->newPoint([9]));

        $iterations = 0;
        $history    = [];
        $callback   = function ($space, $clusters) use (&$iterations, &$history) {
            foreach ($clusters as $cluster) {
                $history[$iterations][] = $cluster->getCoordinates()[0];
            }

            $iterations++;
        };

        $clusters = $space->solve(2, $callback);

        $this->assertEquals([[0,10],[2,8]], $history);
        $this->assertEquals(2, $iterations);
        $this->assertcount(2, $clusters);

        $this->assertEquals([2], $clusters[0]->getCoordinates());
        $this->assertEquals([8], $clusters[1]->getCoordinates());
    }

    public function testSolveSingleCluster()
    {
        $space = new Space(2);
        $space->attach($space->newPoint([0,0]));
        $space->solve(1);
    }

    public function testSolveWithInvalidClustersNumber()
    {
        $this->expectException(\InvalidArgumentException::class);

        $space = new Space(2);
        $space->attach($space->newPoint([0,0]));
        $space->solve(-1);
    }
}
