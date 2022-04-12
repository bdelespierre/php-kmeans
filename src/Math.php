<?php

namespace Kmeans;

class Math
{
    /**
     * @param array<float> $a
     * @param array<float> $b
     */
    public static function euclideanDist(array $a, array $b): float
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
    public static function centroid(array $points): array
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
    public static function gaussianNoise(float $mu, float $sigma): array
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

    public static int $earthRadius = 6371009; // meters

    /**
     * Calculates the great-circle distance (in meters) between two points,
     * with the Haversine formula.
     *
     * @see https://stackoverflow.com/a/14751773/17403258
     *
     * @param array{0: float, 1: float} $from
     * @param array{0: float, 1: float} $to
     * @return float
     */
    public static function haversine($from, $to): float
    {
        return 2 * self::$earthRadius * asin(sqrt(
            pow(sin(deg2rad($to[0] - $from[0]) / 2), 2)
            + cos(deg2rad($from[0])) * cos(deg2rad($to[0]))
            * pow(sin(deg2rad($to[1] - $from[1]) / 2), 2)
        ));
    }

    /**
     * Calculates the centroid of GPS coordinates.
     *
     * @see https://stackoverflow.com/questions/6671183
     *
     * @param array<array{0: float, 1: float}> $points
     * @return array{0: float, 1: float}
     */
    public static function gpsCentroid(array $points): array
    {
        if (count($points) == 1) {
            return $points[0];
        }

        $x = $y = $z = 0;

        foreach ($points as $point) {
            $lat = deg2rad($point[0]);
            $long = deg2rad($point[1]);

            $x += cos($lat) * cos($long);
            $y += cos($lat) * sin($long);
            $z += sin($lat);
        }

        $x /= count($points);
        $y /= count($points);
        $z /= count($points);

        $hypotenuse = sqrt(pow($x, 2) + pow($y, 2));

        $long = atan2($y, $x);
        $lat = atan2($z, $hypotenuse);

        return [rad2deg($lat), rad2deg($long)];
    }
}
