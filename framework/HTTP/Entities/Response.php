<?php

namespace Framework\HTTP\Entities;

class Response {

    private $_action = null;

    /**
     * Response constructor.
     * @param string $action
     */
    public function __construct($action) {
        $this->_action = $action;
    }

    /**
     * @var string
     */
    private $_template = null;

    /**
     * @param string $template
     */
    public function setTemplate($template) {
        $this->_template = $template;
    }

    public function getAction() {
        return $this->_action;
    }

    public function getPureAction() {
        return preg_replace('#Action#', '', $this->getAction());
    }

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->_template;
    }
}