<?php

namespace Framework\HTTP\Entities;

class Request {

    /** @var array */
    private $_get;

    /** @var array */
    private $_post;

    /** @var string */
    private $_requestUri;

    /**
     * @var string
     */
    private $_host;

    public function __construct() {
        $this->_get = $_GET;
        $this->_post = $_POST;
        $this->setRequestUri(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null);
        $this->setHost(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null);
    }

    /**
     * @param string $host
     */
    public function setHost($host) {
        $this->_host = $host;
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
     * @param string $requestUri
     */
    public function setRequestUri($requestUri) {
        $this->_requestUri = $requestUri;
    }

    /**
     * @return string
     */
    public function getRequestUri() {
        return $this->_requestUri;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->_host;
    }

    /**
     * @return string
     */
    public function getStrippedRequestUri() {
        $requestUri = $this->getRequestUri();
        if (($questionMarkPos = strpos($requestUri, '?')) !== false) {
            return substr($requestUri, 0, $questionMarkPos);
        }
    }
}