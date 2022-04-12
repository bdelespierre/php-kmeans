<?php

namespace Tests\Unit;

use Kmeans\Math;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Kmeans\Math
 */
class MathTest extends TestCase
{
    // ------------------------------------------------------------------------
    // Euclidean Distance

    /**
     * @dataProvider euclidianDistanceDataProvider
     * @param array<float> $a
     * @param array<float> $b
     * @param float $dist
     */
    public function testEuclideanDist(array $a, array $b, float $dist): void
    {
        $this->assertEquals(round($dist, 6), round(Math::euclideanDist($a, $b), 6));
    }

    /**
     * @return \Generator<array<mixed>>
     */
    public function euclidianDistanceDataProvider(): \Generator
    {
        /** @var array<string> $row */
        foreach ($this->readCsv('euclidean_distances_2d') as $row) {
            list($x1, $y1, $x2, $y2, $dist) = array_map('floatval', $row);
            yield [[$x1, $y1], [$x2, $y2], $dist];
        }

        /** @var array<string> $row */
        foreach ($this->readCsv('euclidean_distances_3d') as $row) {
            list($x1, $y1, $z1, $x2, $y2, $z2, $dist) = array_map('floatval', $row);
            yield [[$x1, $y1, $z1], [$x2, $y2, $z2], $dist];
        }
    }

    // ------------------------------------------------------------------------
    // Centroid

    /**
     * @dataProvider centroidDataProvider
     * @param array<float> $centroid
     * @param array<float> ...$points
     */
    public function testCentroid(array $centroid, array ...$points): void
    {
        $this->assertEquals($centroid, Math::centroid($points));
    }

    /**
     * @return \Generator<array<array<float>>>
     */
    public function centroidDataProvider(): \Generator
    {
        /** @var array<string> $row */
        foreach ($this->readCsv('centroids_2d') as $row) {
            list($x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4, $cx, $cy) = array_map('floatval', $row);
            yield [[$cx, $cy], [$x1, $y1], [$x2, $y2], [$x3, $y3], [$x4, $y4]];
        }
    }

    // ------------------------------------------------------------------------
    // Gaussian Noise

    /**
     * @dataProvider gaussianNoiseDataProvider
     */
    public function testGaussianNoise(float $mu, float $sigma = 1, float $nb = 1e3): void
    {
        // let's generate $nb numbers and sum them
        for ($sum = 0, $i = 0; $i < $nb; $i++) {
            $sum += array_sum(Math::gaussianNoise($mu, $sigma));
        }

        // cumpute the mean (which should be $mu)
        $sum /= ($nb * 2);

        // verify the mean is around $mu (plus or minus $sigma)
        $this->assertTrue(
            $sum >= $mu - $sigma && $sum <= $mu + $sigma
        );
    }

    /**
     * @return array<mixed>
     */
    public function gaussianNoiseDataProvider(): array
    {
        return [
            ['mu' => 10],
            ['mu' => 100],
            ['mu' => 1000],
            ['mu' => -10],
            ['mu' => -100],
            ['mu' => -1000],
        ];
    }

    // ------------------------------------------------------------------------
    // Haversine

    /**
     * @dataProvider haversineDataProvider
     * @param array{0: float, 1: float} $from
     * @param array{0: float, 1: float} $to
     */
    public function testHaversine(string $label, array $from, array $to, float $expected): void
    {
        $obtained = Math::haversine($from, $to);

        $this->assertLessThan(
            1, // meter
            $obtained - $expected,
            "Haversine distance for $label should be around $expected meters",
        );
    }

    public function haversineDataProvider(): \Generator
    {
        /** @var array<string> $row */
        foreach ($this->readCsv('haversine_distances') as $row) {
            $label = array_shift($row);
            $row = array_map('floatval', $row);
            yield [$label, [$row[0], $row[1]], [$row[2], $row[3]], $row[4]];
        }
    }

    // ------------------------------------------------------------------------
    // GPS Centroid

    /**
     * @dataProvider gpsCentroidDataProvider
     * @param array{0: float, 1: float} $expected
     * @param array<array{0: float, 1: float}> $points
     */
    public function testGpsCentroid(string $label, array $expected, array $points): void
    {
        $obtained = Math::gpsCentroid($points);

        $this->assertLessThan(
            1,
            Math::haversine($expected, $obtained),
            "Centroid of $label should be near " . implode(', ', $expected),
        );
    }

    public function gpsCentroidDataProvider(): \Generator
    {
        /** @var array<string> $row */
        foreach ($this->readCsv('gps_centroid') as $row) {
            $label = array_shift($row);
            $points = array_chunk(array_map('floatval', $row), 2);
            yield [$label, array_shift($points), $points];
        }
    }

    // ------------------------------------------------------------------------
    // Helpers

    private static function readCsv(string $path): \SplFileObject
    {
        $csv = new \SplFileObject(__DIR__ . "/../Data/{$path}.csv");

        $csv->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::READ_AHEAD
        );

        return $csv;
    }
}
