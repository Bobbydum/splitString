<?php

namespace App\Service;

class SubsetProblemSolver
{
    /*
     * Required sum
     */
    public $sum;

    /*
     * Input array of integers
     */
    public $array;

    /*
     * Sum of positive integers in array
     */
    protected $positive;

    /*
     * Sum of negative integers in array
     */
    protected $negative;

    /*
     * Sum of absolute values of all integers in array
    */
    protected $absolute;

    /*
     * Input array size
     */
    protected $size;

    /*
     * Temporary array with number of solutions
     */
    protected $tempValues;

    /*
     * Stack for building path
     */
    protected $pathStack;

    /*
     * Constructor
     */
    public function __construct()
    {
        $this->tempValues = [];
        $this->positive = 0;
        $this->negative = 0;
        $this->absolute = 0;
        $this->pathStack = [];
    }

    /**
     * @param mixed $sum
     */
    public function setSum($sum): void
    {
        $this->sum = $sum;
    }

    /**
     * @param mixed $array
     */
    public function setArray($array): void
    {
        $this->array = $array;
        $this->size = count($this->array);
    }

    /**
     * @return array|bool
     */
    public function solve()
    {
        foreach ($this->array as $el) {
            if ($el > 0) {
                $this->positive += $el;
                $this->absolute += $el;
            } else {
                $this->negative += $el;
                $this->absolute -= $el;
            }
        }

        if (($this->sum > $this->positive) || ($this->sum < $this->negative)) {
            return false;
        }

        for ($a = 0; $a <= $this->absolute; $a++) {
            $this->tempValues[0][$a] = 0;
        }

        $first = $this->array[0] - $this->negative;
        $this->tempValues[0][$first]++;

        for ($i = 1; $i < $this->size; $i++) {
            $el = $this->array[$i];
            for ($a = 0, $b = $this->negative; $a <= $this->absolute; $a++, $b++) {
                $this->tempValues[$i][$a] = 0;

                $skipCurrentEl = $this->tempValues[$i - 1][$a];
                if ($skipCurrentEl) {
                    $this->tempValues[$i][$a] = $skipCurrentEl;
                }

                if ($el == $b) {
                    $this->tempValues[$i][$a]++;
                }

                $minusCurrentElIndex = $a - $el;
                if (($minusCurrentElIndex >= 0) && ($minusCurrentElIndex <= $this->absolute)) {
                    $sumWithCurrentEl = $this->tempValues[$i - 1][$minusCurrentElIndex];
                    if ($sumWithCurrentEl) {
                        $this->tempValues[$i][$a] += $sumWithCurrentEl;
                    }
                }
            }
        }
        $paths = [];
        $this->addToPathStack($this->size - 1, $this->sum, 0);
        $this->buildPaths($paths);

        return $paths;
    }

    private function buildPaths(&$paths)
    {
        while (count($this->pathStack)) {
            $stackTop = array_pop($this->pathStack);

            $currentIndex = $stackTop['index'];
            $requestedSum = $stackTop['sum'];
            $pathIndex = $stackTop['pathIndex'];

            $el = $this->array[$currentIndex];
            $numberOfPaths = $this->tempValues[$currentIndex][$requestedSum - $this->negative]; // 

            if (count($paths) < $numberOfPaths) {
                for ($v = count($paths); $v < $numberOfPaths; $v++) {
                    $paths[$v] = [];
                }
            }

            if ($currentIndex < 0) {
                continue;
            }

            $requestedSumMinusEl = $requestedSum - $el - $this->negative;
            $numberOfPathsWithEl = ($currentIndex && ($requestedSumMinusEl >= 0) && ($requestedSumMinusEl <= $this->absolute)) ? $this->tempValues[$currentIndex - 1][$requestedSumMinusEl] : 0;

            $numberOfPathsWithoutEl = $currentIndex ? $this->tempValues[$currentIndex - 1][$requestedSum - $this->negative] : 0;

            if ($numberOfPathsWithEl) {
                for ($nop = $pathIndex; $nop < $pathIndex + $numberOfPathsWithEl; $nop++) {
                    if (!is_array($paths[$nop])) {
                        $paths[$nop] = [$paths[$nop]];
                    }
                    array_push($paths[$nop], $el);
                }
                $this->addToPathStack($currentIndex - 1, $requestedSum - $el, $pathIndex);
            }

            if ($numberOfPathsWithoutEl) {
                $this->addToPathStack($currentIndex - 1, $requestedSum, $pathIndex + $numberOfPathsWithEl);
            }

            if ($el == $requestedSum) {
                if (!in_array($el, $paths[$pathIndex + $numberOfPathsWithEl])) {
                    array_push($paths[$pathIndex + $numberOfPathsWithEl], $el);
                }
            }
        }

        $tempPaths = [];
        foreach ($paths as $path) {
            if ($path != 0 && count($path) != 0) {
                $tempPaths[] = $path;
            }
        }
        $paths = $tempPaths;
    }

    private function addToPathStack($currentIndex, $requestedSum, $pathIndex)
    {
        array_push(
            $this->pathStack,
            [
                'index'     => $currentIndex,
                'sum'       => $requestedSum,
                'pathIndex' => $pathIndex,
            ]
        );
    }
}