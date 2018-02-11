<?php

namespace Controllers;

use Framework\Request;

class Main {
    public function indexAction(Request $request) {
        echo 'index fired!';
    }

}
