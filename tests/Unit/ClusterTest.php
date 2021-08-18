<?php

namespace Tests\Unit;

use Bdelespierre\Kmeans\Cluster;
use Bdelespierre\Kmeans\Point;
use Bdelespierre\Kmeans\PointCollection;
use Bdelespierre\Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Bdelespierre\Kmeans\Cluster
 * @uses Bdelespierre\Kmeans\Space
 * @uses Bdelespierre\Kmeans\Point
 * @uses Bdelespierre\Kmeans\PointCollection
 */
class ClusterTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getCentroid
     */
    public function testGetCentroid()
    {
        $space = new Space(2);
        $centroid = new Point($space, [0,0]);
        $cluster = new Cluster($centroid, new PointCollection($space));

        $this->assertSame(
            $centroid,
            $cluster->getCentroid()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getPoints
     */
    public function testGetPoints()
    {
        $space = new Space(2);
        $centroid = new Point($space, [0,0]);
        $collection = new PointCollection($space);
        $cluster = new Cluster($centroid, $collection);

        foreach (range(1, 10) as $i) {
            $collection->add(
                new Point($space, [0,$i])
            );
        }

        $this->assertCount(
            10,
            $cluster->getPoints()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::getPoints
     */
    public function testAttach()
    {
        $space = new Space(2);
        $centroid = new Point($space, [0,0]);
        $cluster = new Cluster($centroid);

        foreach (range(1, 10) as $i) {
            $cluster->attach(
                new Point($space, [0,$i])
            );
        }

        $this->assertCount(
            10,
            $cluster->getPoints()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::detach
     * @covers ::getPoints
     */
    public function testDetach()
    {
        $space = new Space(2);
        $centroid = new Point($space, [0,0]);
        $collection = new PointCollection($space);
        $cluster = new Cluster($centroid, $collection);

        $pointA = new Point($space, [1,1]);
        $pointB = new Point($space, [2,2]);
        $pointC = new Point($space, [3,3]);

        $collection->add($pointA);
        $collection->add($pointB);
        $collection->add($pointC);

        $cluster->detach($pointA);
        $cluster->detach($pointC);

        $this->assertCount(
            1,
            $cluster->getPoints()
        );
    }
}
