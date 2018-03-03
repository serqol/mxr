<?php

namespace Framework\Templating\Concern;

use Framework\HTTP\Entities\Response;

trait Render {
    /**
     * @param Response $response
     * @param array $params
     * @return string
     */
    final public function render(Response $response, array $params = []) {
        $template = $response->getTemplate();
        if ($template === null) {
            try {
                $template = self::_getTemplateByController(static::class, $response->getPureAction());
            } catch (\Throwable $t) {
                die($t->getMessage());
            }
        }
        foreach ($params as $key => $param) {
            $template = preg_replace("#{{{$key}}}#", $param, $template);
        }
        return preg_replace('#{{.*}}#', '', $template);
    }

    /**
     * @param $controller
     * @param $action
     * @return string
     * @throws \Exception
     */
    protected function _getTemplateByController($controller, $action) {
        $controllerName = strtolower(substr($controller, strpos($controller, '\\') + 1));
        if (!file_exists($templateFile = __DIR__ . '/../../../www/templates/' . $controllerName . '/' . $action . '.html')) {
            throw new \Exception("Template for controller {$controllerName} was not found in {$templateFile}!");
        }
        return file_get_contents($templateFile);
    }
}