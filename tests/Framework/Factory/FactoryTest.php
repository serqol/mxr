<?php

namespace Tests\Factory;

require_once __DIR__ . '/../../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Framework\Factory\Services\Factory;

class FactoryTest extends TestCase {

    /**
     * @var Factory
     */
    private $_factory;

    protected function setUp() {
        parent::setUp();
        $this->_factory = new Factory();
    }
}