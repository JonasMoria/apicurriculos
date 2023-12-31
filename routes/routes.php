<?php

use App\Controllers\AppController;
use App\Controllers\EnterpriseController;
use App\Controllers\UserController;

use function src\slimConfiguration;

$app = new \Slim\App(slimConfiguration());

$app->get('/', AppController::class . ':getAboutApp');

$app->post('/register/person', UserController::class . ':InsertNewUser');
$app->post('/register/enterprise', EnterpriseController::class . ':InsertNewEnterprise');

$app->run();