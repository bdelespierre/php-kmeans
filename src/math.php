<?php

/**
 * @param array<float> $a
 * @param array<float> $b
 */
function euclidean_dist(array $a, array $b): float
{
    assert(count($a) == count($b));

    for ($dist = 0, $n = 0; $n < count($a); $n++) {
        $dist += pow($a[$n] - $b[$n], 2);
    }

    return sqrt($dist);
}

/**
 * @param array<array<float>> $points
 * @return array<float>
 */
function find_centroid(array $points): array
{
    $centroid = [];

    foreach ($points as $point) {
        foreach ($point as $n => $value) {
            $centroid[$n] = ($centroid[$n] ?? 0) + $value;
        }
    }

    foreach ($centroid as &$value) {
        $value /= count($points);
    }

    return $centroid;
}

/**
 * The standard Box–Muller transform generates values from the standard normal
 * distribution (i.e. standard normal deviates).
 *
 * @see https://en.wikipedia.org/wiki/Box%E2%80%93Muller_transform
 *
 * @return array{float, float}
 */
function generate_gaussian_noise(float $mu, float $sigma): array
{
    static $twoPi = 2 * M_PI;

    // create two random numbers, make sure u1 is greater than epsilon
    do {
        $u1 = (float) mt_rand() / (float) mt_getrandmax();
        $u2 = (float) mt_rand() / (float) mt_getrandmax();
    } while ($u1 < PHP_FLOAT_EPSILON);

    // compute z0 and z1
    $mag = $sigma * sqrt(-2.0 * log($u1));
    $z0 = $mag * cos($twoPi * $u2) + $mu;
    $z1 = $mag * sin($twoPi * $u2) + $mu;

    return [$z0, $z1];
}
