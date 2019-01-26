<?php

namespace App\Service;

/**
 * Class CombineService
 *
 * @package App
 */
class CombineService
{

    /**
     * @param array $array
     *
     * @return array
     */
    public function getCombination(array $array): array
    {
        $combinations = [];
        $words = count($array);
        $combos = 1;
        for ($i = $words; $i > 0; $i--) {
            $combos *= $i;
        }
        while (count($combinations) < $combos) {
            shuffle($array);
            $combo = implode('', $array);
            if (!in_array($combo, $combinations, true)) {
                $combinations[] = $combo;
            }
        }

        $result = [];
        foreach ($combinations as $combination)
        {
            $result[]=str_split($combination);
        }

        return $result;
    }
}