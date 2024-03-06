<?php

use App\Controllers\AppController;
use App\Controllers\CurriculumController;
use App\Models\UserModel;
use App\Controllers\UserController;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Http;
use function src\slimConfiguration;
use App\Exceptions\SqlQueryException;

session_start();

$app = new \Slim\App(slimConfiguration());

// Routes without authentication
$app->group('/api', function() use ($app) {
    $app->post('/register', UserController::class . ':insertNewUser');
    $app->get('/search', AppController::class . ':search');
    $app->get('/view/{id}', AppController::class . ':view');
});

// Authentication
$middlewareAuthPerson = function (Request $request, Response $response, $next) : Response {
    try {
        $userModel = new UserModel();

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass512 = $_SERVER['PHP_AUTH_PW'];
        } else {
            $user = '';
            $pass512 = '';
        }

        $userModel->personAuth($user, $pass512);

        $response = $next($request, $response);

        return $response;

    } catch (SqlQueryException $error) {
        return Http::getJsonReponseError($response, $error->getMessage(), Http::UNAUTHORIZED);

    } catch (\Exception $error) {
        return Http::getJsonResponseErrorServer($response, $error);
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
