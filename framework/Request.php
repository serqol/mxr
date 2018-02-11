<?php

namespace Framework;

class Request {

    /** @var array */
    private $_get;

    /** @var array */
    private $_post;

    /** @var string */
    private $_requestUri;

    public function __construct() {
        $this->_get = $_GET;
        $this->_post = $_POST;
        $this->_requestUri = $_SERVER['REQUEST_URI'];
    }

    /**
     * @param string $param
     * @return mixed
     */
    public function get($param) {
        return array_key_exists($param, $this->_get) ? $this->_get[$param] : null;
    }

    /**
     * @param string $param
     * @return mixed
     */
    public function post($param) {
        return array_key_exists($param, $this->_post) ? $this->_post[$param] : null;
    }

    /**
     * @return string
     */
    public function getRequestUri() {
        return $this->_requestUri;
    }
}