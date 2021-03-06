<?php

namespace Framework\Utils\Services;

use Framework\Factory\Services\Factory;

class Utils {

    /**
     * @param array $input
     * @return array
     */
    public function getListFromArrayValues(array $input) {
        $result = [];

        foreach ($input as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->getListFromArrayValues($item));
                continue;
            }
            $result[] = $item;
        }
        return $result;
    }

    public function getValuesListFromArrayByKey(array $input, $key) {
        $result = [];

        foreach ($input as $k => $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->getValuesListFromArrayByKey($item, $key));
                continue;
            }
            if ($k === $key && is_string($item)) {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * @param $dir
     * @return array
     */
    public function getFilesArrayFromDir($dir) {
        return array_filter(scandir($dir), function ($element) {
            return !in_array($element, ['.', '..']);
        });
    }

    public function getFieldListFromCollection(array $collection, $key) {
        $result = [];
        foreach ($collection as $element) {
            if (is_object($element) && array_key_exists($key, get_object_vars($element))) {
                $result[] = $element->$key;
            } elseif (is_array($element) && array_key_exists($key, $element)) {
                $result[] = $element[$key];
            }
         }

        return $result;
    }

    /**
     * @param $code
     * @return null
     */
    public function getNamespaceFromPHPCode($code) {
        preg_match('#namespace ([A-Za-z\\\]+);#', $code, $matches);
        if (!isset($matches[1])) {
            return null;
        }
        return $matches[1];
    }

    /**
     * @param $fileName
     * @return bool|string
     */
    public function getFileExtension($fileName) {
        return substr(strrchr($fileName, '.'), 1);
    }

    /**
     * @param $code
     * @return null
     */
    public function getClassFromPHPCode($code) {
        preg_match('#class ([A-Za-z\\\]+)#', $code, $matches);
        if (!isset($matches[1])) {
            return null;
        }
        return $matches[1];
    }

    /**
     * @param $file
     * @return string
     */
    public function getFullClassFromFile($file) {
        $contents = file_get_contents($file);
        return $this->getNamespaceFromPHPCode($contents) . '\\' . $this->getClassFromPHPCode($contents);
    }

    /**
     * @param $file
     * @return object
     */
    public function getClassInstanceFromFile($file) {
        return Factory::instance($this->getFullClassFromFile($file));
    }

    /**
     * @param string $className
     * @return string
     */
    public function getClassNameShort($className) {
        if (($slashPosition = strrpos($className, '\\')) === false) {
            return $className;
        }
        return substr($className, $slashPosition + 1);
    }

    public function countingSortNew(array $array) {
        $max = $this->_getMaxValue($array);
        $countArray = array_pad([], $max + 1, 0);

        for ($i = 0; $i < count($array); $i++) {
            $countArray[$array[$i]]++;
        }

        for ($i = 1; $i < count($countArray); $i++) {
            $countArray[$i] += $countArray[$i - 1];
        }

        array_unshift($countArray, 0);
        array_pop($countArray);

        $sorted = array_pad([], count($array), 0);
        for ($i = 0; $i < count($array); $i++) {
            $sorted[$countArray[$array[$i]]] = $array[$i];
            $countArray[$array[$i]]++;
        }

        return $sorted;
    }

    /**
     * @param array $array
     * @return array
     */
    public function quickSort($array) {
        if (count($array) <= 1) {
            return $array;
        }
        $pivot = array_pop($array);
        $smallerValues = [];
        $biggerValues = [];
        while (count($array) > 0) {
            if (($element = array_shift($array)) > $pivot) {
                $biggerValues[] = $element;
            } else {
                $smallerValues[] = $element;
            }
        }
        return (count($biggerValues) <= 1 && count($smallerValues) <= 1)
            ? ($temp = array_merge($smallerValues, [$pivot], $biggerValues))
            : array_merge($this->quickSort($smallerValues), [$pivot], $this->quickSort($biggerValues));
    }

    /**
     * @param $array
     * @return array
     */
    public function mergeSort($array) {
        $result = [];
        $mid = count($array) / 2;
        if ($mid >= 1) {
            $leftHalf = array_slice($array, 0, $mid);
            $rightHalf = array_slice($array, $mid);
            $leftHalf = $this->mergeSort($leftHalf);
            $rightHalf = $this->mergeSort($rightHalf);

            while (count($leftHalf) > 0 || count($rightHalf) > 0) {
                $leftValue = array_shift($leftHalf);
                $rightValue = array_shift($rightHalf);
                if ($leftValue === null) {
                    $result[] = $rightValue;
                    continue;
                } elseif ($rightValue === null) {
                    $result[] = $leftValue;
                    continue;
                }
                if ($leftValue < $rightValue) {
                    $result[] = $leftValue;
                    array_unshift($rightHalf, $rightValue);
                } else {
                    $result[] = $rightValue;
                    array_unshift($leftHalf, $leftValue);
                }
            }
        } else {
            return $array;
        }
        return $result;
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function bubbleSort($array) {
        for ($i = count($array); $i > 0; $i--) {
            for ($j = 1; $j < $i; $j++) {
                if ($array[$j] < $array[$j-1]) {
                    $temp = $array[$j-1];
                    $array[$j-1] = $array[$j];
                    $array[$j] = $temp;
                }
            }
        }
        return $array;
    }

    /**
     * @param array $array
     * @return array
     */
    public function insertionSort($array) {
        $sorted[] = array_shift($array);
        while (count($array) > 0) {
            $shifted = array_shift($array);
            for ($i = count($sorted); $i > 0; $i--) {
                if ($shifted > $sorted[$i-1]) {
                    $firstPart = array_slice($sorted, 0, $i);
                    $lastPart = array_slice($sorted, $i);
                    $sorted = array_merge($firstPart, [$shifted], $lastPart);
                    break;
                } elseif ($i === 1) {
                    array_unshift($sorted, $shifted);
                    break;
                }
            }
        }
        return $sorted;
    }

    public function insertionSortNew(array $array) {
        $result = $array;

        for ($i = 0; $i < count($result); $i++) {
            $unsortedElement = $result[$i];
            for ($j = $i; $j > 0 && $unsortedElement < $result[$j-1]; $j--) {
                $result[$j] = $result[$j - 1];
            }
            $result[$j] = $unsortedElement;
        }

        return $result;


    }

    /**
     * @param array $array
     * @return array
     */
    public function selectionSort($array) {
        $result = [];
        $arrayCount = count($array);
        for ($i = 0; $i < $arrayCount; $i++) {
            $min = current($array);
            $minKey = key($array);
            for ($j = 1; $j < count($array); $j++) {
                if ($array[$j] < $min) {
                    $min = $array[$j];
                    $minKey = $j;
                }
            }
            $result[] = $array[$minKey];
            unset($array[$minKey]);
            $array = array_values($array);
        }
        return $result;
    }

    /**
     * @param array $array
     * @return array
     */
    public function heapSort($array) {
        $result = [];
        $maxHeap = $this->_buildMaxHeap($array);
        $lastElement = count($maxHeap) - 1;
        while ($lastElement > 0) {
            $maxHeap = $this->_swap($maxHeap, 0, $lastElement);
            $maxHeap = $this->_heapify($maxHeap, 0, $lastElement);
            $lastElement--;
        }

        return $maxHeap;
    }

    /**
     * @param array $array
     * @return array
     */
    public function countingSort(array $array) {
        $result = [];
        $countArray = array_pad([], $this->_getMaxValue($array) + 1, 0);
        foreach ($array as $element) {
            $countArray[$element] += 1;
        }
        foreach ($countArray as $key => $item) {
            for ($j = 0; $j < $item; $j++) {
                $result[] = $key;
            }
        }
        return $result;
    }

    public function radixSortNew(array $array, $queues = 10) {
        $queues = array_pad([], $queues, []);
        $max = current($array);
        foreach ($array as $element) {
            if ($element > $max) {
                $max = $element;
            }
            array_push($queues[$element % 10], $element);
        }
        $i = 0;
        foreach ($queues as $k => &$que) {
            while (!empty($queues[$k])) {
                $array[$i++] = array_shift($que);
            }
        }
        $depth = 2;
        while ($depth <= strlen((string)$max)) {
            foreach ($array as $element) {
                $digit = $depth <= strlen($element) ? (int)substr((int)($element % (pow(10, $depth))), 0, 1) : 0;
                var_dump([
                    'digits' => $digit,
                    'element' => $element,
                    'depth' => $depth,
                ]);
                array_push($queues[$digit], $element);
            }
            $i = 0;
            foreach ($queues as $k => &$que) {
                while (!empty($queues[$k])) {
                    $array[$i++] = array_shift($que);
                }
            }
            $depth++;
        }
        return $array;
    }

    public function radixSort(array $array) {
        $depth = 0;
        $maxDepth = strlen(current($array));
        foreach ($array as $element) {
            if (($len = strlen($element)) > $maxDepth) {
                $maxDepth = $len;
            }
        }
        $array = array_map(function ($element) {
            return array_reverse(str_split((string)$element));
        }, $array);

        while ($depth < $maxDepth) {
            $array = $this->_sortByDepth($array, $depth++);
        }
        return array_map(function ($element) {
            return (int)implode('', array_reverse($element));
        }, $array);
    }

    private function _sortByDepth(array $array, $depth) {
        $countingSort = array_pad([], 10, ['count' => 0, 'values' => []]);
        for ($i = 0; $i < count($array); $i++) {
            if (!isset($array[$i][$depth])) {
                $array[$i][$depth] = 0;
            }
            $countingSort[$array[$i][$depth]]['count']++;
            $countingSort[$array[$i][$depth]]['values'][] = $array[$i];
        }
        $sorted = [];
        for ($j = 0; $j < count($countingSort); $j++) {
            while ($countingSort[$j]['count']-- > 0) {
                $sorted[] = array_shift($countingSort[$j]['values']);
            }
        }

        return $sorted;
    }

    public function crazySort(array $array) {
        $result = [];
        $countArray = array_pad([], $this->_getMaxValue($array) + 1, 0);
        foreach ($array as $element) {
            $countArray[$element] += 1;
        }
        foreach ($countArray as $key => $item) {
            for ($j = 0; $j < $item; $j++) {
                $result[] = $key;
            }
        }
        return $result;
    }

    private function _getMinValue(array $array) {
        $min = array_shift($array);
        foreach ($array as $element) {
            if ($element < $min) {
                $min = $element;
            }
        }
        return $min;
    }

    private function _getMaxValue(array $array) {
        $max = array_shift($array);
        while (count($array) > 0) {
            $max = ($next = array_shift($array)) > $max ? $next : $max;
        }
        return $max;
    }

    private function _buildMaxHeap(array $array) {
        $arrayLength = count($array);
        $index = floor($arrayLength / 2 - 1);
        while ($index >= 0) {
            $array = $this->_heapify($array, $index, $arrayLength);
            $index--;
        }
        return $array;
    }

    private function _heapify($array, $index, $arrayLength) {

        while ($index < $arrayLength) {

            $indexToSwap = $index;

            $leftChildIndex =  2 * $index + 1;
            $rightChildIndex = $leftChildIndex + 1;

            if ($leftChildIndex < $arrayLength && $array[$leftChildIndex] > $array[$indexToSwap]) {
                $indexToSwap = $leftChildIndex;
            }

            if ($rightChildIndex < $arrayLength && $array[$rightChildIndex] > $array[$indexToSwap]) {
                $indexToSwap = $rightChildIndex;
            }

            if ($indexToSwap === $index) {
                break;
            }

            $array =  $this->_swap($array, $index, $indexToSwap);

            $index = $indexToSwap;
        }

        return $array;
    }

    private function _swap(array $array, $firstIndex, $secondIndex) {
        $temp = $array[$firstIndex];
        $array[$firstIndex] = $array[$secondIndex];
        $array[$secondIndex] = $temp;
        return $array;
    }
}