<?php

namespace App\Controllers;

use App\Models\EnterpriseModel;
use App\Models\Http;
use App\Models\Security;
use Exception;
use InvalidArgumentException;
use Slim\Http\Request;
use Slim\Http\Response;

class EnterpriseController {
    private $model;

    public function __construct() {
        $this->model = new EnterpriseModel();
    }

    public function InsertNewEnterprise(Request $request, Response $response, array $args) : Response {
        $enterprise = $this->model;
        $params = $request->getParsedBody();

        try {
            $enterprise->setName(Security::sanitizeString($params['name']));
            $enterprise->setEmail(Security::sanitizeEmail($params['email']));
            $enterprise->setPassword($params['password']);

            $enterprise->insert($enterprise);
            $json = Http::obtainJsonSuccess('Empresa cadastrada com sucesso');

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