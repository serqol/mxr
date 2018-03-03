<?php

namespace Framework\ORM\Services;

use Exceptions\DataBase;
use Framework\Config\Services\Loader;

class Connection {

    private $_connection;

    private $_testConnection;

    /**
     * @param Loader $settingsLoader
     */
    public function __construct(Loader $settingsLoader) {
        $dbSettings = $settingsLoader->getSetting('Database');
        $this->_connection = mysqli_connect($dbSettings['host'], $dbSettings['login'], $dbSettings['password'], $dbSettings['db_name'], 3001);
        $this->_testConnection = mysqli_connect($dbSettings['host'], $dbSettings['login'], $dbSettings['password'], $dbSettings['test_db_name'], 3001);
    }

    public function setTestContext() {
        $this->_connection = $this->_testConnection;
    }

    /**
     * @param callable $callback
     * @param array $args
     */
    public function executeInTestConnectionContext(callable $callback, array $args) {
        $cachedConnection = $this->_connection;
        $this->_connection = $this->_testConnection;
        $callback(...$args);
        $this->_connection = $cachedConnection;
    }

    /**
     * @param string $query
     * @return bool|\mysqli_result
     * @throws DataBase
     */
    public function query($query) {
        if ($result = mysqli_query($this->_connection, $query)) {
            return $result;
        }
        throw new DataBase(mysqli_error($this->_connection));
    }
}