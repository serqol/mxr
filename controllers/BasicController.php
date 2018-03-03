<?php

namespace Controllers;

use Framework\ORM\Services\Orm;
use Framework\Routing\Services\Router;
use Framework\Templating\Concern\Render;
use Framework\Templating\Services\TemplatingEngine;

class BasicController {

    use Render;

    private $_router;

    private $_templating;

    private $_orm;

    public function __construct(Router $router, TemplatingEngine $templating, Orm $orm) {
        $this->_router = $router;
        $this->_templating = $templating;
        $this->_orm = $orm;
    }
}