<?php

namespace App\Controllers;

use App\Models\EnterpriseModel;
use App\Models\Http;
use App\Models\Security;
use Slim\Http\Request;
use Slim\Http\Response;

use App\Exceptions\InvalidParamException;
use App\Exceptions\SqlQueryException;

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
            return Http::getJsonReponseSuccess($response, [], 'Empresa Cadastrada Com Sucesso', Http::CREATED);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }

    }
}