<?php

namespace Tests\Unit;

use Bdelespierre\Kmeans\Cluster;
use Bdelespierre\Kmeans\ClusterCollection;
use Bdelespierre\Kmeans\Interfaces\ClusterInterface;
use Bdelespierre\Kmeans\Point;
use Bdelespierre\Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Bdelespierre\Kmeans\ClusterCollection
 * @uses Bdelespierre\Kmeans\Space
 * @uses Bdelespierre\Kmeans\Cluster
 * @uses Bdelespierre\Kmeans\Point
 * @uses Bdelespierre\Kmeans\PointCollection
 */
class ClusterCollectionTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::has
     * @covers ::remove
     */
    public function testAddingAndRemovingClustersFromCollection()
    {
        $space = new Space(4);
        $collection = new ClusterCollection($space);

        $clusterA = new Cluster(new Point($space, [1,2,3,4]));
        $clusterB = new Cluster(new Point($space, [5,6,7,8]));
        $clusterC = new Cluster(new Point($space, [9,0,1,2]));

        $collection->add($clusterA);
        $collection->add($clusterC);

        $this->assertTrue($collection->has($clusterA));
        $this->assertFalse($collection->has($clusterB));
        $this->assertTrue($collection->has($clusterC));

        $collection->remove($clusterC);
        $this->assertFalse($collection->has($clusterC));
    }

    /**
     * @covers ::__construct
     * @covers ::add
     */
    public function testAddingInvalidClusterToCollection()
    {
        $this->expectException(\InvalidArgumentException::class);

        $spaceA = new Space(2);
        $spaceB = new Space(3);

        $collection = new ClusterCollection($spaceA);
        $cluster = new Cluster(new Point($spaceB, [1, 2, 3]));

        $collection->add($cluster);
    }

    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::remove
     * @covers ::count
     */
    public function testCount()
    {
        $space = new Space(4);
        $collection = new ClusterCollection($space);

        $clusterA = new Cluster(new Point($space, [1,2,3,4]));
        $clusterB = new Cluster(new Point($space, [5,6,7,8]));
        $clusterC = new Cluster(new Point($space, [9,0,1,2]));

        $collection->add($clusterA);
        $collection->add($clusterB);
        $collection->add($clusterC);

        $this->assertEquals(3, count($collection));

        $collection->remove($clusterA);
        $this->assertEquals(2, count($collection));

        $collection->remove($clusterB);
        $this->assertEquals(1, count($collection));

        $collection->remove($clusterC);
        $this->assertEquals(0, count($collection));
    }

    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     */
    public function testIterator()
    {
        $space = new Space(4);
        $collection = new ClusterCollection($space);

        $clusterA = new Cluster(new Point($space, [1,2,3,4]));
        $clusterB = new Cluster(new Point($space, [5,6,7,8]));
        $clusterC = new Cluster(new Point($space, [9,0,1,2]));

        $collection->add($clusterA);
        $collection->add($clusterB);
        $collection->add($clusterC);

        $iterations = 0;
        foreach ($collection as $i => $cluster) {
            $this->assertInstanceof(ClusterInterface::class, $cluster);
            $iterations++;
        }

        $this->assertEquals(3, $iterations);
    }
}
