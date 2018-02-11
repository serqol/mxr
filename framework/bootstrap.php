<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Framework\Request;
use Framework\Router;

$request = new Request();
$router  = new Router();

try {
    $route = $router->match($request->getRequestUri());
    $controllerName = $route->getController();
    $actionName = $route->getAction();
    $controller = new $controllerName;
    return $controller->$actionName($request);
} catch (\Exceptions\NotFound $t) {
    die($t->getMessage());
}