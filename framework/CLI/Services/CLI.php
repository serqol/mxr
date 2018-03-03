<?php

namespace Framework\CLI\Services;

use Framework\Annotation\Services\Annotation;
use Framework\CLI\Entities\Command;
use Framework\CLI\Providers\Basic;
use Framework\Factory\Services\Factory;
use Framework\Utils\Services\Utils;

class CLI {

    const PROVIDERS_PATH = __DIR__ . '/../Providers';

    /**
     * @var Utils
     */
    private $_utils;

    /**
     * @var Annotation
     */
    private $_annotation;

    /**
     * @var array
     */
    private $_commandsByClasses;

    /**
     * @var array
     */
    private $_commands = [];

    public function __construct(Utils $utils, Annotation $annotation) {
        $this->_utils = $utils;
        $this->_annotation = $annotation;
        $this->_commandsByClasses = $this->getCommandsByClasses();
        foreach ($this->_commandsByClasses as $commandsByClass) {
            $this->_commands = array_merge($this->_commands, $this->_utils->getFieldListFromCollection($commandsByClass, '_name'));
        }
    }

    public function getCommands() {
        return $this->_commands;
    }

    /**
     * @return Command[]
     */
    public function getCommandsByClasses() {
        $result = [];
        $commandsClasses = $this->_getCommandsClasses();
        foreach ($commandsClasses as $commandClass) {
            try {
                $result[$commandClass] = $this->_getCommands($commandClass);
            } catch (\Throwable $t) {
                echo $t->getMessage();
                continue;
            }
        }
        return $result;
    }

    /**
     * @param $commandClass
     * @return array
     * @throws \ReflectionException
     */
    private function _getCommands($commandClass) {
        $result = [];
        $reflection = new \ReflectionClass($commandClass);
        $methods = array_filter($reflection->getMethods(), function ($method) {
            return $method->name !== '__construct';
        });
        foreach ($methods as $method) {
            $command = new Command();
            $command->setName($this->_annotation->getClassMethodParamValue($commandClass, $method->name, 'command'));
            $command->setDescription($this->_annotation->getClassMethodParamValue($commandClass, $method->name, 'description'));
            $command->setCall($method);
            $result[] = $command;
        }
        return $result;
    }

    private function _getCommandsClasses() {
        $result = [];
        $commandsFiles = $this->_utils->getFilesArrayFromDir(self::PROVIDERS_PATH);
        foreach ($commandsFiles as $commandFile) {
            if (!is_subclass_of($commandClass = $this->_utils->getFullClassFromFile(self::PROVIDERS_PATH . '/' . $commandFile), Basic::class)) {
                continue;
            }
            $result[] = $commandClass;
        }
        return $result;
    }

    public function getUserCall($userMethod, array $userArguments) {
        if ($this->_commandsByClasses === null) {
            return null;
        }
        /**
         * @var string $class
         * @var Command[] $commands
         */
        foreach ($this->_commandsByClasses as $class => $commands) {
            foreach ($commands as $command) {
                if ($userMethod === $command) {
                    if ($this->_annotation->getClassMethodRequiredParamsCount($class, $command) !== count($userArguments)) {
                        die('Required parameters were not provided');
                    }
                    $classInstance = Factory::instance($class);
                    $methodCall = $command->getCall();
                    return $classInstance->$methodCall();
                }
            }
        }
        return false;
    }
}