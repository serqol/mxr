<?php

namespace Tests\Framework\HTTP\Entity;

use PHPUnit\Framework\TestCase;
use Framework\HTTP\Entities\Request;

class RequestTest extends TestCase {

    /**
     * @var Request
     */
    private $_request;

    protected function setUp() {
        parent::setUp();
        $this->_request = new Request();
    }

    /**
     * @param string $input
     * @param string $expected
     * @dataProvider getStrippedRequestUriDataProvider
     */
    public function testGetStrippedRequestUri($input, $expected) {
        $this->_request->setRequestUri($input);
        $this->assertEquals($expected, $this->_request->getStrippedRequestUri());
    }

    /**
     * @return array
     */
    public function getStrippedRequestUriDataProvider() {
        return [
            ['localhost?',                            'localhost'],
            ['www.avgn.com?tits=kewl&notits=notkewl', 'www.avgn.com'],
        ];
    }
}