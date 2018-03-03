<?php

namespace Tests\Framework\ORM;

use Framework\Factory\Services\Factory;
use Framework\ORM\Interfaces\Entity;
use Framework\ORM\Services\Schema;
use PHPUnit\Framework\TestCase;
use Tests\Framework\Mock\User;

class SchemaTest extends TestCase {

    /** @var Schema */
    private $_schema;

    protected function setUp() {
        parent::setUp();
        $this->_schema = Factory::instance(Schema::class);
        $this->_schema->useTestConnection();
        $this->_schema->dropAllSchemas();
    }

    /**
     * @param Entity $entity
     * @dataProvider createSchemaForEntityDataProvider
     */
    public function testCreateSchemaForEntity(Entity $entity) {
        $this->assertTrue($this->_schema->createSchemaForEntity($entity));
    }

    public function testDropAll() {
        $this->_schema->createSchemaForEntity(new User());
        $this->_schema->dropAllSchemas();
        $this->assertEmpty($this->_schema->getSchemaTables());
    }

    public function testCreateAllSchemasFromDir() {
        $this->assertTrue($this->_schema->createAllSchemasFromDir());
    }

    /**
     * @param array $input
     * @param array $expected
     * @dataProvider getSchemaTablesDataProvider
     */
    public function testGetSchemaTables(array $input, array $expected) {
        foreach ($input as $entity) {
            $this->_schema->createSchemaForEntity($entity);
        }
        $this->assertEquals($this->_schema->getSchemaTables(), $expected);
    }

    /**
     * @return array
     */
    public function getSchemaTablesDataProvider() {
        return [
            [[new User()], ['user']]
        ];
    }

    /**
     * @param $entity
     * @param $isShouldCreateSchema
     * @param $expected
     * @dataProvider schemaExistsDataProvider
     */
    public function testSchemaExists($entity, $isShouldCreateSchema, $expected) {
        if ($isShouldCreateSchema) {
            $this->_schema->createSchemaForEntity($entity);
        }
        $this->assertEquals($expected, $this->_schema->schemaExists($entity));
    }

    /**
     * @return array
     */
    public function schemaExistsDataProvider() {
        return [
            [new User(), true, true],
            [new User(), false, false]
        ];
    }

    public function createSchemaForEntityDataProvider() {
        return [
            [new User()],
        ];
    }
}