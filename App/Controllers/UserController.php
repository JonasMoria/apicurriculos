<?php

namespace App\Controllers;

use App\Models\Http;
use App\Models\Security;
use App\Models\UserModel;
use Exception;
use InvalidArgumentException;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function InsertNewUser(Request $request, Response $response, array $arg) : Response {
        $user = $this->model;
        $params = $request->getParsedBody();

        try {

            $user->setName(Security::sanitizeString($params['name']));
            $user->setEmail(Security::sanitizeEmail($params['email']));
            $user->setPassword($params['password']);

            $user->insert($user);
            $json = Http::obtainJsonSuccess('Usuário cadastrado com sucesso');

            $response->getBody()->write($json);
            
        } catch (InvalidArgumentException $error) {
            $response->getBody()->write(
                Http::obtainJsonError($error->getMessage())
            );
        } catch (Exception $error) {
            $response->getBody()->write(
                Http::obtainJsonError($error->getMessage())
            );
        }

        return $response;
    }
}