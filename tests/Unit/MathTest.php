<?php

namespace Tests\Unit;

use Kmeans\Math;
use Kmeans\findCentroid;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Kmeans\Math
 */
class MathTest extends TestCase
{
    /**
     * @covers ::euclideanDist
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
        foreach ($this->openCsv('euclidean_distances_2d.csv') as $row) {
            list($x1, $y1, $x2, $y2, $dist) = array_map('floatval', $row);
            yield [[$x1, $y1], [$x2, $y2], $dist];
        }

        /** @var array<string> $row */
        foreach ($this->openCsv('euclidean_distances_3d.csv') as $row) {
            list($x1, $y1, $z1, $x2, $y2, $z2, $dist) = array_map('floatval', $row);
            yield [[$x1, $y1, $z1], [$x2, $y2, $z2], $dist];
        }
    }

    /**
     * @covers ::centroid
     * @dataProvider centroidDataProvider
     * @param array<float> $centroid
     * @param array<float> ...$points
     */
    public function testFindCentroid(array $centroid, array ...$points): void
    {
        $this->assertEquals($centroid, Math::centroid($points));
    }

    /**
     * @return \Generator<array<array<float>>>
     */
    public function centroidDataProvider(): \Generator
    {
        /** @var array<string> $row */
        foreach ($this->openCsv('centroids_2d.csv') as $row) {
            list($x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4, $cx, $cy) = array_map('floatval', $row);
            yield [[$cx, $cy], [$x1, $y1], [$x2, $y2], [$x3, $y3], [$x4, $y4]];
        }
    }

    /**
     * @return \Generator<array<array<float>>>
     */
    public function boundariesDataProvider(): \Generator
    {
        /** @var array<string> $row */
        foreach ($this->openCsv('boundaries_2d.csv') as $row) {
            list($x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4, $x5, $y5, $ax, $ay, $bx, $by) = array_map('floatval', $row);
            yield [[$ax, $ay], [$bx, $by], [$x1, $y1], [$x2, $y2], [$x3, $y3], [$x4, $y4], [$x5, $y5]];
        }
    }

    /**
     * @return array<mixed>
     */
    public function frandDataProvider(): array
    {
        return [
            ['min' => 0, 'max' => 1],
            ['min' => 10, 'max' => 20],
            ['min' => 0, 'max' => 100],
            ['min' => -100, 'max' => 100],
            ['min' => -1e6, 'max' => 1e6],
        ];
    }

    /**
     * @covers ::gaussianNoise
     * @dataProvider gaussianNoiseDataProvider
     */
    public function testGenerateGaussianNoise(float $mu, float $sigma = 1, float $nb = 1e3): void
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

    private static function openCsv(string $path): \SplFileObject
    {
        $csv = new \SplFileObject(__DIR__ . '/../Data/' . $path);
        $csv->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::READ_AHEAD);

        return $csv;
    }
}
