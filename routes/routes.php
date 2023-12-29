<?php

use App\Controllers\AppController;

use function src\slimConfiguration;

$app = new \Slim\App(slimConfiguration());

$app->get('/', AppController::class . ':getAboutApp');

$app->run();