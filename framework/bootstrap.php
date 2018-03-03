<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Framework\Factory\Services\Factory;
use Framework\Routing\Services\Router;
use Framework\HTTP\Entities\Response;

$GLOBALS['config']['config_path'] = __DIR__ . '/config.yml';
$GLOBALS['config']['routes_path'] = __DIR__ . '/routes.yml';

/**
 * @var Router $router
 */
$router = Factory::instance(Router::class);

try {
    $route      = $router->match();
    $actionName = $route->getAction();
    $controller = $router->getControllerByRouteName($route->getName());
    echo $controller->$actionName($router->getRequest(), new Response($actionName));
} catch (\Exceptions\NotFound $t) {
    die($t->getMessage());
}