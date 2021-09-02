<?php

namespace Tests\Unit;

use Kmeans\Cluster;
use Kmeans\ClusterCollection;
use Kmeans\Interfaces\ClusterInterface;
use Kmeans\Point;
use Kmeans\Space;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\ClusterCollection
 * @uses \Kmeans\Space
 * @uses \Kmeans\Cluster
 * @uses \Kmeans\Point
 * @uses \Kmeans\PointCollection
 */
class ClusterCollectionTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getSpace
     * @covers ::attach
     * @covers ::contains
     */
    public function testConstructingClusterWithPoints(): void
    {
        $space = new Space(1);
        $point = new Point($space, [1]);
        $cluster = new Cluster($point);
        $collection = new ClusterCollection($space, [$cluster]);

        $this->assertTrue(
            $collection->contains($cluster)
        );

        $this->assertFalse(
            $collection->contains(new Cluster($point))
        );
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::contains
     * @covers ::detach
     */
    public function testAddingAndRemovingClustersFromCollection(): void
    {
        $space = new Space(4);
        $collection = new ClusterCollection($space);

        $clusterA = new Cluster(new Point($space, [1,2,3,4]));
        $clusterB = new Cluster(new Point($space, [5,6,7,8]));
        $clusterC = new Cluster(new Point($space, [9,0,1,2]));

        $collection->attach($clusterA);
        $collection->attach($clusterC);

        $this->assertTrue(
            $collection->contains($clusterA)
        );

        $this->assertFalse(
            $collection->contains($clusterB)
        );

        $this->assertTrue(
            $collection->contains($clusterC)
        );

        $collection->detach($clusterC);

        $this->assertFalse(
            $collection->contains($clusterC)
        );
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     */
    public function testAddingInvalidClusterToCollection(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $spaceA = new Space(2);
        $spaceB = new Space(3);

        $collection = new ClusterCollection($spaceA);
        $cluster = new Cluster(new Point($spaceB, [1, 2, 3]));

        $collection->attach($cluster);
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::detach
     * @covers ::count
     */
    public function testCount(): void
    {
        $space = new Space(4);
        $collection = new ClusterCollection($space);

        $clusterA = new Cluster(new Point($space, [1,2,3,4]));
        $clusterB = new Cluster(new Point($space, [5,6,7,8]));
        $clusterC = new Cluster(new Point($space, [9,0,1,2]));

        $collection->attach($clusterA);
        $collection->attach($clusterB);
        $collection->attach($clusterC);

        $this->assertEquals(3, count($collection));

        $collection->detach($clusterA);
        $this->assertEquals(2, count($collection));

        $collection->detach($clusterB);
        $this->assertEquals(1, count($collection));

        $collection->detach($clusterC);
        $this->assertEquals(0, count($collection));
    }

    /**
     * @covers ::__construct
     * @covers ::attach
     * @covers ::current
     * @covers ::key
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     */
    public function testIterator(): void
    {
        $space = new Space(4);
        $collection = new ClusterCollection($space);

        $clusterA = new Cluster(new Point($space, [1,2,3,4]));
        $clusterB = new Cluster(new Point($space, [5,6,7,8]));
        $clusterC = new Cluster(new Point($space, [9,0,1,2]));

        $collection->attach($clusterA);
        $collection->attach($clusterB);
        $collection->attach($clusterC);

        $iterations = 0;
        foreach ($collection as $i => $cluster) {
            $this->assertInstanceof(ClusterInterface::class, $cluster);
            $iterations++;
        }

        $this->assertEquals(3, $iterations);
    }
}
