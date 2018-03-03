<?php

namespace Tests\Framework\Annotation;

use Framework\Annotation\Services\Annotation;
use Framework\Factory\Services\Factory;
use PHPUnit\Framework\TestCase;
use Tests\Framework\Mock\User;

class AnnotationTest extends TestCase {

    /** @var Annotation */
    protected $_annotationService;

    protected function setUp() {
        parent::setUp();
        $this->_annotationService = Factory::instance(Annotation::class);
    }

    /**
     * @param $class
     * @param $param
     * @param $expected
     * @dataProvider getClassParamValueDataProvider
     */
    public function testGetClassParamValue($class, $param, $expected) {
        $this->assertEquals($expected, $this->_annotationService->getClassParamValue($class, $param));
    }

    /**
     * @param $class
     * @param $variable
     * @param $param
     * @param $expected
     * @dataProvider getClassVariableParamValueDataProvider
     */
    public function testGetClassVariableParamValue($class, $variable, $param, $expected) {
        $this->assertEquals($expected, $this->_annotationService->getClassVariableParamValue($class, $variable, $param));
    }

    public function getClassVariableParamValueDataProvider() {
        return [
            [User::class, '_name', 'type', 'VARCHAR(20)'],
        ];
    }

    public function getClassParamValueDataProvider() {
        return [
            [User::class, 'table', 'user'],
        ];
    }
}