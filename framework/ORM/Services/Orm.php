<?php

namespace Framework\ORM\Services;

use Framework\ORM\Interfaces\Entity;

class Orm {

    private $_entities;

    /**
     * @var Connection
     */
    private $_connection;

    public function __construct(Connection $connection) {
        $this->_connection = $connection;
    }

    public function commit(Entity $entity) {
    }

    public function flush() {
    }
}