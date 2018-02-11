<?php

namespace Controllers;

use Framework\Request;

class Authorization {

    public function authorizeAction(Request $request) {
        $login = $request->post('login');
        $password = $request->post('password');
    }
}