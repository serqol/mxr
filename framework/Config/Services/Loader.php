<?php

namespace Framework\Config\Services;

class Loader {

    private $_config;

    /**
     * Loader constructor.
     */
    public function __construct() {
        $this->_config = yaml_parse_file($GLOBALS['config']['config_path']);
    }

    /**
     * @param string $setting
     * @return mixed
     */
    public function getSetting($setting) {
        if (!isset($this->_config[$setting])) {
            return null;
        }
        return $this->_config[$setting];
    }
}