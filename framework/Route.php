<?php

namespace Framework;

class Route {

    /**
     * Route constructor.
     * @param string $name
     * @param string $controller
     * @param string $action
     */
    public function __construct($name, $controller, $action) {
        $this->setName($name);
        $this->setAction($action);
        $this->setController($controller);
    }

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_controller;

    /**
     * @var string
     */
    private $_action;

    /**
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getController() {
        return $this->_controller;
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->_action;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction($action) {
        $this->_action = $action;
        return $this;
    }

    /**
     * @param $controller
     * @return $this
     */
    public function setController($controller) {
        $this->_controller = $controller;
        return $this;
    }
}