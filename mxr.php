<?php
/**
 * MXR Framework CLI tool
 */

require_once __DIR__ . '/vendor/autoload.php';

use Framework\Factory\Services\Factory;
use Framework\CLI\Services\CLI;
use Framework\Utils\Services\Utils;
use Framework\CLI\Entities\Command;

/** @var CLI $cliService */
$cliService = Factory::instance(CLI::class);
/** @var Utils $utils */
$utils = Factory::instance(Utils::class);

$commandsByClass = $cliService->getCommandsByClasses();

$showHelp = function ($commandsByClass) use ($utils, $cliService) {
    echo "Available commands: \n";
    foreach ($commandsByClass as $class => $commands) {
        echo "{$utils->getClassNameShort($class)} Provider: \n";
        /** @var Command $command */
        foreach ($commands as $command) {
            echo "{$command->getName()} \n";
        }
    }
    echo "\n\n";
};

$callInput = isset($argv[1]) ? trim($argv[1]) : null;

if ($callInput === null) {
    $showHelp($commandsByClass);
}

$callArgs = isset($argv[2]) ? array_splice($argv, 2);

if (in_array($callInput, $cliService->getCommands())) {
    try {
        $cliService->getUserCall($callInput, $callArgs);
    } catch (\Throwable $t) {
        die($t->getMessage());
    }
}
