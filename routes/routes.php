<?php

use App\Controllers\AppController;
use App\Controllers\CurriculumController;
use App\Controllers\EnterpriseController;
use App\Controllers\UserController;
use App\Middlewares\basicAuth;

use function src\slimConfiguration;

$app = new \Slim\App(slimConfiguration());

$app->get('/', AppController::class . ':getAboutApp');

$app->group('/register', function() use ($app) {
   $app->post('/person', UserController::class . ':InsertNewUser');
   $app->post('/enterprise', EnterpriseController::class . ':InsertNewEnterprise');
});

// person routes with authentication
$app->group('/person', function() use ($app) {
   $app->post('/curriculum/new', CurriculumController::class . ':newCurriculum');

})->add(basicAuth::authPerson());

$app->run();