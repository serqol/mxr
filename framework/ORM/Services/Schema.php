<?php

namespace Framework\ORM\Services;

use Exceptions\DataBase;
use Framework\Annotation\Services\Annotation;
use Framework\ORM\Interfaces\Entity;
use Framework\Utils\Services\Utils;
use Framework\ORM\Entities\BasicEntity;

class Schema {

    /**
     * @var Connection
     */
    private $_connection;

    /**
     * @var Annotation
     */
    private $_annotationService;

    /**
     * @var Utils
     */
    private $_utils;

    /**
     * Schema constructor.
     * @param Connection $connection
     * @param Annotation $annotation
     * @param Utils $utils
     */
    public function __construct(Connection $connection, Annotation $annotation, Utils $utils) {
        $this->_connection = $connection;
        $this->_annotationService = $annotation;
        $this->_utils = $utils;
    }

    /**
     *
     */
    public function useTestConnection() {
        $this->_connection->setTestContext();
    }

    /**
     * @return array
     */
    public function getSchemaTables() {
        return $this->_getSchemaTables();
    }

    /**
     * @param Entity $entity
     * @return bool|\mysqli_result
     * @throws DataBase
     */
    public function dropSchemaForEntity(Entity $entity) {
        $tableName = $this->_annotationService->getClassParamValue($entity->getClass(), 'table');
        return $this->_connection->query("DROP TABLE {$tableName};");
    }

    /**
     * @return bool
     */
    public function dropAllSchemas() {
        $tables = $this->_getSchemaTables();
        foreach ($tables as $table) {
            $query = "DROP TABLE IF EXISTS {$table};";
            try {
                $this->_connection->query($query);
            } catch (\Throwable $t) {
                echo $t->getMessage();
            }
        }
        return true;
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function createSchemaForEntity(Entity $entity) {
        return $this->_createSchemaForEntity($entity);
    }

    /**
     * @param string $dir
     * @return bool
     * @throws \Exception
     */
    public function createAllSchemasFromDir($dir = __DIR__ . '/../../../entities') {
        $phpFiles = array_filter($this->_utils->getFilesArrayFromDir($dir), function ($file) {
            return $this->_utils->getFileExtension($file) === 'php';
        });
        if (count($phpFiles) === 0) {
            throw new \Exception('No php classes found in provided folder');
        }
        foreach ($phpFiles as $phpFile) {
            $class = $this->_utils->getFullClassFromFile($dir . '/' . $phpFile);
            if (!is_subclass_of($class, BasicEntity::class)) {
                continue;
            }
            $entity = new $class();
            $this->_createSchemaForEntity($entity);
        }
        return true;
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function schemaExists(Entity $entity) {
        return $this->_schemaExists($entity);
    }

    /**
     * @param $tableName
     * @param array $varTypesByVars
     * @return bool
     */
    private function _createTable($tableName, array $varTypesByVars) {
        $varsQuery = 'id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL';
        foreach ($varTypesByVars as $var => $varType) {
            $varsQuery .= ", {$var} {$varType}";
        }
        $query = "CREATE TABLE {$tableName} ({$varsQuery});";
        try {
            $this->_connection->query($query);
        } catch (DataBase $exception) {
            die($exception->getMessage());
        }
        return true;
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    private function _schemaExists(Entity $entity) {
        $tableName = $this->_getTableName($entity);
        $tables = $this->_getSchemaTables();
        return in_array($tableName, $tables);
    }

    /**
     * @param Entity $entity
     * @return mixed|null
     */
    private function _getTableName(Entity $entity) {
        return $this->_annotationService->getClassParamValue($entity->getClass(), 'table');
    }

    /**
     * @return array
     */
    private function _getSchemaTables() {
        $result = [];
        try {
            $queryResult = $this->_connection->query('SHOW TABLES');
            $result = $queryResult->fetch_all();
        } catch (DataBase $t) {
            echo $t->getMessage();
        }
        return $this->_utils->getListFromArrayValues($result);
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    private function _createSchemaForEntity(Entity $entity) {
        $entityClass = $entity->getClass();
        $entityVars = array_keys(get_class_vars($entity->getClass()));
        $tableName = $this->_getTableName($entity);
        $varTypesByVars = [];
        foreach ($entityVars as $var) {
            $varType = $this->_annotationService->getClassVariableParamValue($entityClass, $var, 'type');
            $varTypesByVars[$var] = $varType;
        }
        return $this->_createTable($tableName, $varTypesByVars);
    }

}