<?php

namespace Tests\Framework\CLI;

use Framework\CLI\Services\CLI;
use Framework\Factory\Services\Factory;
use PHPUnit\Framework\TestCase;

class CLITest extends TestCase {

    /**
     * @var CLI
     */
    private $_cliService;

    protected function setUp() {
        parent::setUp();
        $this->_cliService = Factory::instance(CLI::class);
    }

    public function testGetCommands() {
        var_dump($this->_cliService->getCommandsByClasses());
        $this->assertTrue(true);
    }
}