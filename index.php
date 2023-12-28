<?php

use Slim\Http\Request;
use Slim\Http\Response;

require_once 'vendor/autoload.php';

$app = new \Slim\App();

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('API Currículos v0.1 beta');
});

$app->run();