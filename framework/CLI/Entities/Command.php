<?php

namespace Framework\CLI\Entities;

class Command {

    public $_name;

    private $description;

    private $call;

    private $parameters;

    /**
     * @return mixed
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * @param mixed $_name
     */
    public function setName($_name) {
        $this->_name = $_name;
    }

    /**
     * @return mixed
     */
    public function getCall() {
        return $this->call;
    }

    /**
     * @param mixed $call
     */
    public function setCall($call) {
        $this->call = $call;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    public function getParameters() {
        return $this->parameters;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }
}