<?php

namespace Framework\Routing\Services;

use Exceptions\NotFound;
use Framework\HTTP\Entities\Request;
use Framework\Routing\Entity\Route;
use Framework\Factory\Services\Factory;

class Router {

    /**
     * @var array
     */
    private $_routes;

    /**
     * @var string
     */
    private $_basePath;

    /**
     * @var Request
     */
    private $_request;

    /**
     * Router constructor.
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->_request = $request;
        $this->_routes = yaml_parse_file($GLOBALS['config']['routes_path']);
    }

    /**
     * @throws NotFound
     * @return Route
     */
    public function match() {
        $matches = explode('/', $this->_request->getRequestUri());
        $isActionSpecified = count($matches) > 1 && end($matches) !== '';
        $action = $isActionSpecified ? array_pop($matches) . 'Action' : 'indexAction';
        $controllerKey = implode('/' ,array_filter($matches, function($element) {
            return $element !== '';
        })) ?: '/';

        if (!array_key_exists($controllerKey, $this->_routes)) {
            throw new NotFound("Controller {$controllerKey} was not found");
        }

        $controller = "Controllers\\" . $this->_routes[$controllerKey]['controller'];

        if (!in_array($action, get_class_methods($controller))) {
            throw new NotFound("Action {$action} in controller {$controller} was not found");
        }

        $routeName = $this->_routes[$controllerKey]['name'];

        return new Route($routeName, $controller, $action);
    }

    public function getControllerByRouteName($routeName) {
        $controllerClass = $this->getControllerClassByRouteName($routeName);
        return Factory::instance($controllerClass);
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * @param string $routeName
     * @return string
     */
    public function getControllerClassByRouteName($routeName) {
        foreach ($this->_routes as $route) {
            if ($routeName === $route['name']) {
                return "Controllers\\" . $route['controller'];
            }
        }
    }

    /**
     * @param string $routeName
     * @return string
     */
    public function generate($routeName) {
        foreach ($this->_routes as $path => $route) {
            if ($routeName === $route['name']) {
                return $path;
            }
        }
    }

    public function redirectByRouteName($routeName) {
        $path = $this->generate($routeName);
        $this->_redirect($path);
    }

    private function _redirect($path) {
        header("Location: {$path}");
    }

    public function getBasePath() {
        return $this->_basePath;
    }

    /**
     * @return array
     */
    public function getRoutes() {
        return $this->_routes;
    }
}