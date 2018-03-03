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
}