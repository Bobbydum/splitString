<?php

namespace App\Service;

/**
 * Class SplitStringService
 *
 * @package App\Service
 */
class SplitStringService
{
    /**
     * @param $string
     * @param array $patterns
     *
     * @return array
     */
    public function getSplitedString($string, array $patterns): array
    {
        $start = 0;
        $result = [];
        foreach ($patterns as $pattern) {
            $result[] = substr($string, $start, $pattern);
            $start = +$pattern;
        }

        return $result;
    }

    /**
     * @param $string
     * @param $maxLength
     *
     * @return array
     */
    public function getSplitLength($string, $maxLength): array
    {
        $result = [];
        $stringLength = strlen($string);
        $x = 1;
        do {

            $a = intdiv($stringLength, $x);
            $b = $stringLength % $x;
            if ($a >= $maxLength && ($b >= $maxLength || $b == 0)) {
                $result[] = $a;
            }
        } while ($x++ <= $maxLength);

        return $result;
    }
}