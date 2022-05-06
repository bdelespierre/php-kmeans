# PHP Kmean

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bdelespierre/php-kmeans.svg?style=flat-square)](https://packagist.org/packages/bdelespierre/php-kmeans)
[![Build Status](https://img.shields.io/travis/bdelespierre/php-kmeans/master.svg?style=flat-square)](https://travis-ci.org/bdelespierre/php-kmeans)
[![Quality Score](https://img.shields.io/scrutinizer/g/bdelespierre/php-kmeans.svg?style=flat-square)](https://scrutinizer-ci.com/g/bdelespierre/php-kmeans)
[![Total Downloads](https://img.shields.io/packagist/dt/bdelespierre/php-kmeans.svg?style=flat-square)](https://packagist.org/packages/bdelespierre/php-kmean)

[K-mean](http://en.wikipedia.org/wiki/K-means_clustering) clustering algorithm implementation in PHP.

## Installation

You can install the package via composer:

```bash
composer require bdelespierre/php-kmeans
```

## Usage

```PHP
require "vendor/autoload.php";

$data = [
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

// create a 2-dimentions space
$space = new Kmeans\Space(2);

// prepare the points
$points = new Kmeans\PointCollection($space);

foreach ($data as $coordinates) {
    $points->attach(new Kmeans\Point($space, $coordinates));
}

// prepare the algorithm
$algorithm = new Kmeans\Algorithm(new Kmeans\RandomInitialization());

// cluster these 50 points in 3 clusters
$clusters = $algorithm->clusterize($points, 3);

// display the cluster centers and attached points
foreach ($clusters as $num => $cluster) {
    $coordinates = $cluster->getCentroid()->getCoordinates();
    printf(
        "Cluster #%s [%d,%d] has %d points\n",
        $num,
        $coordinates[0],
        $coordinates[1],
        count($cluster->getPoints())
    );
}
```

**Note:** the example is given with points of a 2D space but it will work with any dimention greater than or equal to 1.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email benjamin.delespierre@gmail.com instead of using the issue tracker.

## Credits

- [Benjamin Delespierre](https://github.com/bdelespierre)
- [Ron Cemer](https://github.com/roncemer)
- [All Contributors](../../contributors)

## License

Lesser General Public License (LGPL). Please see [License File](LICENSE.md) for more information.
