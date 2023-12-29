<?php

namespace App\Controllers;

use App\Models\AppModel;
use Slim\Http\Request;
use Slim\Http\Response;

class AppController {
    public function getAboutApp(Request $request, Response $response, array $args) : Response {
        $appModel = new AppModel();

        $json = $appModel->getAboutApp();

        $response->getBody()->write(
            json_encode($json)
        );

        return $response;
    }
}