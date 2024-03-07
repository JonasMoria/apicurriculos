<?php

use App\Controllers\AppController;
use App\Controllers\CurriculumController;
use App\Controllers\UserController;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Http;
use function src\slimConfiguration;
use App\Models\Security;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

session_start();

$app = new \Slim\App(slimConfiguration());

// Routes without authentication
$app->group('/api', function() use ($app) {
    $app->post('/login', AppController::class . ':login');
    $app->post('/register', UserController::class . ':insertNewUser');
    $app->get('/search', AppController::class . ':search');
    $app->get('/view/{id}', AppController::class . ':view');

});

// Authentication
$middlewareAuthPerson = function (Request $request, Response $response, $next) : Response {
    try {
        $jwtToken = Security::filterJwtToken($request->getServerParam('HTTP_AUTHORIZATION'));
        $data = JWT::decode($jwtToken, new Key(Security::getJwtkey(), 'HS256'));

        AppController::createSession($data);

        $response = $next($request, $response);

        return $response;

    } catch (Throwable $error) {
        return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

    }
};

// Person routes with authentication
$app->group('/perfil', function() use ($app){
    $app->get('/view', UserController::class . ':viewPerfil');
    $app->put('/update', UserController::class . ':updatePerfil');
    $app->delete('/delete', UserController::class . ':disableAccount');

})->add($middlewareAuthPerson);

// Curriculum routes with authentication
$app->group('/curriculum', function() use ($app) {
    $app->post('/new', CurriculumController::class . ':new');
    $app->get('/list', CurriculumController::class . ':list');
    $app->get('/view/{id}', CurriculumController::class . ':view');
    $app->put('/update/{id}', CurriculumController::class . ':update');
    $app->delete('/delete/{id}', CurriculumController::class . ':delete');

})->add($middlewareAuthPerson);

$app->run();
