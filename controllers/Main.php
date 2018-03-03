<?php

namespace Controllers;

use Framework\HTTP\Entities\Request;
use Framework\HTTP\Entities\Response;

class Main extends BasicController {

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     */
    public function indexAction(Request $request, Response $response) {
        return $this->render($response, [
            'title' => 'Авторизация'
        ]);
    }
}
