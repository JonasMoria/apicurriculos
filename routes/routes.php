<?php

use App\Controllers\AppController;
use App\Controllers\CurriculumController;
use App\Models\UserModel;
use App\Controllers\EnterpriseController;
use App\Controllers\UserController;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Http;
use function src\slimConfiguration;
use App\Exceptions\SqlQueryException;

session_start();

$app = new \Slim\App(slimConfiguration());

$app->get('/', AppController::class . ':getAboutApp');

$app->group('/register', function() use ($app) {
   $app->post('/person', UserController::class . ':InsertNewUser');
   $app->post('/enterprise', EnterpriseController::class . ':InsertNewEnterprise');
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

       $auth = $userModel->personAuth($user, $pass512);

       $response = $next($request, $response);

       return $response;

   } catch (SqlQueryException $error) {
       return Http::getJsonReponseError($response, $error->getMessage(), Http::UNAUTHORIZED);

   } catch (\Exception $error) {
       return Http::getJsonResponseErrorServer($response, $error);
   }
};

// person routes with authentication
$app->group('/person', function() use ($app) {
   $app->post('/curriculum/new', CurriculumController::class . ':new');
   $app->get('/curriculum/view/{id}', CurriculumController::class . ':view');

})->add($middlewareAuthPerson);

$app->run();
