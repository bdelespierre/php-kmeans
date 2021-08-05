<?php

namespace Tests\Kmeans;

use KMeans\Cluster;
use KMeans\Point;
use KMeans\Space;
use PHPUnit\Framework\TestCase;

class ClusterTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf(
            Cluster::class,
            new Cluster(new Space(2), [0,0])
        );
    }

    public function testToArray()
    {
        $space   = new Space(2);
        $cluster = new Cluster($space, [0,0]);
        $points  = [
            new Point($space, [0,0]),
            new Point($space, [1,1]),
            new Point($space, [2,2]),
        ];

        foreach ($points as $point) {
            $cluster->attach($point);
        }

        $this->assertEquals(
            [
                'centroid' => $points[0]->toArray(),
                'points'   => array_map(
                    function ($p) {
                        return $p->toArray();
                    },
                    $points
                ),
            ],
            $cluster->toArray()
        );
    }

    public function testAttach()
    {
        $space   = new Space(2);
        $cluster = new Cluster($space, [0,0]);
        $points  = [
            new Point($space, [0,0]),
            new Point($space, [1,1]),
            new Point($space, [2,2]),
        ];

        foreach ($points as $point) {
            $cluster->attach($point);
        }

        $this->assertCount(3, $cluster);
    }

    public function testAttachException()
    {
        $this->expectException(\LogicException::class);

        $space   = new Space(2);
        $cluster = new Cluster($space, [0,0]);

        $cluster->attach($cluster);
    }

    public function testDetach()
    {
        $space   = new Space(2);
        $cluster = new Cluster($space, [0,0]);
        $points  = [
            new Point($space, [0,0]),
            new Point($space, [1,1]),
            new Point($space, [2,2]),
        ];

        foreach ($points as $point) {
            $cluster->attach($point);
        }

        $cluster->detach($points[0]);
        $this->assertCount(2, $cluster);

        $cluster->detach($points[1]);
        $this->assertCount(1, $cluster);

        $cluster->detach($points[2]);
        $this->assertCount(0, $cluster);
    }

    public function testAttachAll()
    {
        $space   = new Space(2);
        $cluster = new Cluster($space, [0,0]);
        $points  = [
            new Point($space, [0,0]),
            new Point($space, [1,1]),
            new Point($space, [2,2]),
        ];

        $storage = new \SplObjectStorage();
        foreach ($points as $point) {
            $storage->attach($point);
        }

        $cluster->attachAll($storage);
        $this->assertCount(3, $cluster);
    }

    public function testDetachAll()
    {
        $space   = new Space(2);
        $cluster = new Cluster($space, [0,0]);
        $points  = [
            new Point($space, [0,0]),
            new Point($space, [1,1]),
            new Point($space, [2,2]),
        ];

        foreach ($points as $point) {
            $cluster->attach($point);
        }

        $storage = new \SplObjectStorage();
        foreach ($points as $point) {
            $storage->attach($point);
        }

        $cluster->detachAll($storage);
        $this->assertCount(0, $cluster);
    }

    public function testUpdateCentroid()
    {
        $space   = new Space(1);
        $cluster = new Cluster($space, [0]);

        $cluster->updateCentroid();
        $this->assertEquals([0], $cluster->getCoordinates());

        $cluster->attach(new Point($space, [5]));
        $cluster->attach(new Point($space, [6]));
        $cluster->attach(new Point($space, [7]));

        $cluster->updateCentroid();

        $this->assertEquals([6], $cluster->getCoordinates());
    }

    public function testGetIterator()
    {
        $space   = new Space(2);
        $cluster = new Cluster($space, [0,0]);
        $points  = [
            new Point($space, [0,0]),
            new Point($space, [1,1]),
            new Point($space, [2,2]),
        ];

        foreach ($points as $point) {
            $cluster->attach($point);
        }

        $this->assertInstanceOf(
            \SplObjectStorage::class,
            $cluster->getIterator()
        );
    }

    public function testCount()
    {
        $space   = new Space(2);
        $cluster = new Cluster($space, [0,0]);
        $points  = [
            new Point($space, [0,0]),
            new Point($space, [1,1]),
            new Point($space, [2,2]),
        ];

        $cluster->attach($points[0]);
        $this->assertEquals(1, $cluster->count());

        $cluster->attach($points[1]);
        $this->assertEquals(2, $cluster->count());

        $cluster->attach($points[2]);
        $this->assertEquals(3, $cluster->count());

        $cluster->detach($points[2]);
        $this->assertEquals(2, $cluster->count());

        $cluster->detach($points[1]);
        $this->assertEquals(1, $cluster->count());

        $cluster->detach($points[0]);
        $this->assertEquals(0, $cluster->count());
    }
}
