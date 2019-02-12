<?php

namespace Multis\Lib;

class Math
{
    /**
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @return float
     */
    public static function getDistance(int $x1, int $y1, int $x2, int $y2): float
    {
        return sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));
    }
}
