<?php

namespace Framework\Factory\Services;

class Factory {

    private static $_instances = [];

    /**
     * @param $class
     * @return object
     */
    public static function instance($class) {
        $dependencies = [];
        try {
            $dependencyClasses = Factory::getClassDependencies($class);
        } catch (\Throwable $t) {
            die($t->getMessage());
        }
        foreach ($dependencyClasses as $dependencyClass) {
            $dependencies[] = self::instance($dependencyClass);
        }

        if (array_key_exists($class, self::$_instances)) {
            return self::$_instances[$class];
        };

        return self::$_instances[$class] = new $class(...$dependencies);
    }

    /**
     * @param string $class
     * @throws \Throwable
     * @return string[]
     */
    public static function getClassDependencies($class) {
        $result = [];
        if (!method_exists($class, '__construct')) {
            return $result;
        }
        $reflectionMethod = new \ReflectionMethod($class, '__construct');
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $result[] = $parameter->getClass()->getName();
        }
        return $result;
    }
}