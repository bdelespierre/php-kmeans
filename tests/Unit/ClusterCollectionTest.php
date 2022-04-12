<?php

namespace Tests\Unit;

use Kmeans\Cluster;
use Kmeans\ClusterCollection;
use Kmeans\Euclidean\Point;
use Kmeans\Euclidean\Space;
use Kmeans\Interfaces\ClusterInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\ClusterCollection
 * @uses \Kmeans\Cluster
 * @uses \Kmeans\Euclidean\Point
 * @uses \Kmeans\Euclidean\Space
 * @uses \Kmeans\PointCollection
 */
class ClusterCollectionTest extends TestCase
{
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

    public function testAddingInvalidClusterToCollection(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $spaceA = new Space(2);
        $spaceB = new Space(3);

        $collection = new ClusterCollection($spaceA);
        $cluster = new Cluster(new Point($spaceB, [1, 2, 3]));

        $collection->attach($cluster);
    }

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
