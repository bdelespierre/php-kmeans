# PHP K-Means
_Clustering made simple_

<bloquote>k-means clustering is a method of vector quantization, originally from signal processing, that is popular for cluster analysis in data mining. k-means clustering aims to partition n observations into k clusters in which each observation belongs to the cluster with the nearest mean, serving as a prototype of the cluster. This results in a partitioning of the data space into Voronoi cells.</bloquote>

Read more on [Wikipedia](http://en.wikipedia.org/wiki/K-means_clustering)

PHP K-Means, like its name suggest, is an implementation of K-Means and K-Means++ algorithms for the PHP plateform. It works with an unlimited number of dimentions.

## Usage

Given the following points of R²

```PHP
$points = [
    [80,55],[86,59],[19,85],[41,47],[57,58],
	[76,22],[94,60],[13,93],[90,48],[52,54],
	[62,46],[88,44],[85,24],[63,14],[51,40],
	[75,31],[86,62],[81,95],[47,22],[43,95],
	[71,19],[17,65],[69,21],[59,60],[59,12],
	[15,22],[49,93],[56,35],[18,20],[39,59],
	[50,15],[81,36],[67,62],[32,15],[75,65],
	[10,47],[75,18],[13,45],[30,62],[95,79],
	[64,11],[92,14],[94,49],[39,13],[60,68],
	[62,10],[74,44],[37,42],[97,60],[47,73],
];
```

We want to find 3 clusters:

```PHP
// create a 2 dimentionnal space and fill it
$space = new KMeans\Space(2);

foreach ($points as $point)
    $space->addPoint($point);

 // resolve 3 clusters
$clusters = $space->solve(3);
```

Now we can retrieve each cluster's centroid (the average meaning amongts its points) and all the points in it:

```PHP
foreach ($clusters as $i => $cluster)
    printf("Cluster %d [%d,%d]: %d points\n", $i, $cluster[0], $cluster[1], count($cluster));
```

Example of output:

```
Cluster 0 [79,58]: 18 points
Cluster 1 [57,19]: 19 points
Cluster 2 [31,66]: 13 points
```

### Heads up!

K-Means algorithm is non-deterministic so you may get different results when running it multiple times with the same data. The more points you add in the space, the more accurate the result will be.

You are strongly advised to read the Wikipedia article thoroughly before using this library.

## K-Means++

When triggering the `Kmeans\Space::solve` method, you may provide an alternative seeding method in order to initialize the clusters with the [David Arthur and Sergei Vassilvitskii algorithm](http://en.wikipedia.org/wiki/K-means%2B%2B) which avoids poor clustering results.

```PHP
// resolve 3 clusters using David Arthur and Sergei Vassilvitskii seeding algorithm
$clusters = $space->solve(3, KMeans\Space::SEED_DASV);
```

## Howto

### Get coordinates of a point/cluster:
```PHP
$x = $point[0];
$y = $point[1];

// or

list($x,$y) = $point->getCoordinates();
```

### List all points of a space/cluster:

```PHP
foreach ($cluster as $point)
    printf('[%d,%d]', $point[0], $point[1]);
```

### Attach data to a point:

```PHP
$space->addPoint($coordinate, $data);
```

### Retrieve point data:

```PHP
$data = $space[$point];
```

### Watch the algorithm run

Each iteration step can be monitored using a callback function passed to `Kmeans\Space::solve`:

```PHP
$clusters = $space->solve(3, KMeans\Space::SEED_DEFAULT, function($space, $clusters) {
    static $iterations = 0;

    printf("Iteration: %d\n", ++$iterations);

    foreach ($clusters as $i => $cluster)
        printf("Cluster %d [%d,%d]: %d points\n", $i, $cluster[0], $cluster[1], count($cluster));
});
```