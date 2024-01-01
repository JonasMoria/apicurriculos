<?php

use App\Controllers\AppController;
use App\Controllers\CurriculumController;
use App\Controllers\EnterpriseController;
use App\Controllers\UserController;
use App\Middlewares\basicAuth;

use function src\slimConfiguration;

$app = new \Slim\App(slimConfiguration());

$app->get('/', AppController::class . ':getAboutApp');

$app->post('/register/person', UserController::class . ':InsertNewUser');
$app->post('/register/enterprise', EnterpriseController::class . ':InsertNewEnterprise');

// person routes with authentication
$app->group('', function() use ($app) {
   $app->post('/person/curriculum/new', CurriculumController::class . ':newCurriculum');

})->add(basicAuth::authPerson());

$app->run();