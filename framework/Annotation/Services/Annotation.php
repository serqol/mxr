<?php

namespace Framework\Annotation\Services;

class Annotation {

    /**
     * @param string $class
     * @param string $param
     * @return string
     */
    public function getClassParamValue($class, $param) {
        $matches = [];
        try {
            $reflection = $this->_getClassReflection($class);
            $phpDoc = $reflection->getDocComment();
            preg_match("#@{$param} (.+)#", $phpDoc, $matches);
        } catch (\Throwable $t) {
            echo $t->getMessage();
        }
        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * @param $class
     * @param string $variable
     * @param string $param
     * @return string
     */
    public function getClassVariableParamValue($class, $variable, $param) {
        $matches = [];
        try {
            $reflection = $this->_getClassReflection($class);
            $phpDoc = $reflection->getProperty($variable)->getDocComment();
            preg_match("#@{$param} (.+)#", $phpDoc, $matches);

        } catch (\Throwable $t) {
            echo $t->getMessage();
        }
        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * @param $class
     * @param string $method
     * @param string $param
     * @return string
     */
    public function getClassMethodParamValue($class, $method, $param) {
        $matches = [];
        try {
            $reflection = $this->_getClassReflection($class);
            $phpDoc = $reflection->getMethod($method)->getDocComment();
            preg_match("#@{$param} (.+)#", $phpDoc, $matches);
        } catch (\Throwable $t) {
            echo $t->getMessage();
        }
        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * @param string $class
     * @param string $method
     * @return array
     */
    public function getClassMethodParams($class, $method) {
        try {
            $reflection = $this->_getClassReflection($class);
            $methodReflection = $reflection->getMethod($method);
            return $methodReflection->getParameters();

        } catch (\Throwable $t) {
            $t->getMessage();
        }
        return null;
    }

    /**
     * @param string $class
     * @param string $method
     * @return int
     */
    public function getClassMethodRequiredParamsCount($class, $method) {
        try {
            $reflection = $this->_getClassReflection($class);
            $methodReflection = $reflection->getMethod($method);
            return $methodReflection->getNumberOfRequiredParameters();
        } catch (\Throwable $t) {
            echo $t->getMessage();
        }
        return null;
    }

    /**
     * @param $class
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private function _getClassReflection($class) {
        return new \ReflectionClass($class);
    }
}