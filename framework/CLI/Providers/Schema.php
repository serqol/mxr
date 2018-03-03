<?php

namespace Framework\CLI\Providers;

class Schema extends Basic {

    /**
     * @var \Framework\ORM\Services\Schema
     */
    private $_schemaService;

    /**
     * Schema constructor.
     * @param \Framework\ORM\Services\Schema $schema
     */
    public function __construct(\Framework\ORM\Services\Schema $schema) {
        $this->_schemaService = $schema;
    }

    /**
     * @command create_schema
     * @description Creates schema from entities found in provided directory. Usage: create_schema {dir}. By default dir is __ROOT__ . /entities
     * @param string $dir
     * @return bool
     */
    public function createSchemaForDir($dir) {
        $result = false;
        try {
            $result = $this->_schemaService->createAllSchemasFromDir($dir);
        } catch (\Throwable $t) {
            echo $t->getMessage();
        }
        return $result;
    }
}