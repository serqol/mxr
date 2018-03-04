<?php

namespace Tests\Framework\Utils;

use Framework\Factory\Services\Factory;
use PHPUnit\Framework\TestCase;
use Framework\Utils\Services\Utils;

class UtilsTest extends TestCase {

    /**
     * @var Utils
     */
    private $_utils;

    protected function setUp() {
        parent::setUp();
        $this->_utils = Factory::instance(Utils::class);
    }

    /**
     * @param array $input
     * @param array $expected
     * @dataProvider getListFromArrayValuesDataProvider
     */
    public function testGetListFromArrayValues(array $input, array $expected) {
        $this->assertEquals($expected, $this->_utils->getListFromArrayValues($input));
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider getClassFromFilesDataProvider
     */
    public function testGetClassFromFile($input, $expected) {
        $this->assertEquals($expected, $this->_utils->getFullClassFromFile($input));
    }

    public function getClassFromFilesDataProvider() {
        return [
            [__DIR__ . '/../Mock/User.php', 'Tests\Framework\Mock\User']
        ];
    }

    /**
     * @param array $input
     * @param array $expected
     * @param string $key
     * @dataProvider getValuesListFromArrayKeyDataProvider
     */
    public function testGetValuesListFromArrayKey(array $input, $key, array $expected) {
        $this->assertEquals($expected, $this->_utils->getValuesListFromArrayByKey($input, $key));
    }

    /**
     * @return array
     */
    public function getValuesListFromArrayKeyDataProvider() {
        return [
            [
                    [
                        'more' => ['good' => ['good' => 'moar']],
                        'one' => ['good' => 'value'],
                        'good' => 'blablabla',
                        'man' => ['good' => ['tits' => 'great']],
                    ],
                    'good',
                    ['moar', 'value', 'blablabla'],

            ]
        ]
            ;
    }

    /**
     * @param string $input
     * @param string $expected
     * @dataProvider getClassNameShortDataProvider
     */
    public function testGetClassNameShort($input, $expected) {
        $this->assertEquals($expected, $this->_utils->getClassNameShort($input));
    }

    /**
     * @return array
     */
    public function getClassNameShortDataProvider() {
        return [
            ['Tests\Framework\Mock\User', 'User'],
        ];
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider getNamespaceFromPHPCodeDataProvider
     */
    public function testGetNamespaceFromPHPCode($input, $expected) {
        $this->assertEquals($expected, $this->_utils->getNamespaceFromPHPCode($input));
    }

    public function getNamespaceFromPHPCodeDataProvider() {
        return [
            [file_get_contents(__DIR__ . '/../Mock/User.php'), 'Tests\Framework\Mock']
        ];
    }

    /**
     * @param $array
     * @param $expected
     * @dataProvider quickSortDataProvider
     */
    public function testQuickSort($array, $expected) {
        $this->assertEquals($expected, $this->_utils->quickSort($array));
    }

    /**
     * @return array
     */
    public function quickSortDataProvider() {
        return [
            [
                [5,3,21,7,4,53],
                [3,4,5,7,21,53],
            ],
            [
                [5,3,4,5,5,5,6,1,2],
                [1,2,3,4,5,5,5,5,6],
            ]
        ];
    }

    public function getListFromArrayValuesDataProvider() {
        return [
            [
                'tits' => [
                    'pro' => 'tits',
                    'kewl' => [
                        'item' => 'list',
                        'dudes' => 'pro',
                    ],
                ],
                ['tits', 'list', 'pro'],
            ]
        ];
    }
}