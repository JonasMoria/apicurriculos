<?php

use App\Controllers\AppController;
use App\Controllers\CurriculumController;
use App\Controllers\UserController;
use App\Models\Http;
use function src\slimConfiguration;
use App\Models\Security;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Tuupola\Middleware\JwtAuthentication;

session_start();

$app = new \Slim\App(slimConfiguration());

// Routes without authentication
$app->group('/api', function() use ($app) {
    $app->post('/login', AppController::class . ':login');
    $app->post('/register', UserController::class . ':insertNewUser');
    $app->get('/search', AppController::class . ':search');
    $app->get('/view/{id}', AppController::class . ':view');

});

// Person routes with authentication
$app->group('/perfil', function() use ($app){
    $app->get('/view', UserController::class . ':viewPerfil');
    $app->put('/update', UserController::class . ':updatePerfil');
    $app->delete('/delete', UserController::class . ':disableAccount');

})->add(new JwtAuthentication([
    'secret' => Security::getJwtkey(),
    'before' => function($request) {
        $jwtToken = Security::filterJwtToken($request->getServerParam('HTTP_AUTHORIZATION'));
        $data = JWT::decode($jwtToken, new Key(Security::getJwtkey(), 'HS256'));
        AppController::createSession($data);
    },
    'error' => function($response) {
        return Http::getJsonReponseError($response, 'Acesso não autorizado', Http::BAD_REQUEST);
    }
]));

// Curriculum routes with authentication
$app->group('/curriculum', function() use ($app) {
    $app->post('/new', CurriculumController::class . ':new');
    $app->get('/list', CurriculumController::class . ':list');
    $app->get('/view/{id}', CurriculumController::class . ':view');
    $app->put('/update/{id}', CurriculumController::class . ':update');
    $app->delete('/delete/{id}', CurriculumController::class . ':delete');

})->add(new JwtAuthentication([
    'secret' => Security::getJwtkey(),
    'before' => function($request) {
        $jwtToken = Security::filterJwtToken($request->getServerParam('HTTP_AUTHORIZATION'));
        $data = JWT::decode($jwtToken, new Key(Security::getJwtkey(), 'HS256'));
        AppController::createSession($data);
    },
    'error' => function($response) {
        return Http::getJsonReponseError($response, 'Acesso não autorizado', Http::BAD_REQUEST);
    }
]));

$app->run();
