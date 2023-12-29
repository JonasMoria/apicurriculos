<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class AppController {
    public function getAboutApp(Request $request, Response $response, array $args) : Response {
        $response->getBody()->write('hello word');

        return $response;
    }
}