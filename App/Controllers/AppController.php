<?php

namespace App\Controllers;

use App\Exceptions\InvalidParamException;
use App\Exceptions\SqlQueryException;

use App\Models\AppModel;
use App\Models\Http;
use App\Models\Security;
use Slim\Http\Request;
use Slim\Http\Response;

class AppController {
    private $model;

    public function __construct() {
        $this->model = new AppModel();
    }

    public function login(Request $request, Response $response, array $args) : Response {
        $app = $this->model;
        $params = $request->getParsedBody();

        try {
            if (empty($params)) {
                throw new SqlQueryException('Acesso não autorizado', Http::UNAUTHORIZED);
            }

            $user = Security::validateEmail($params['email']);
            $password = Security::convertToSha512($params['pass']);
            $token = $app->getJwtToken($user, $password);

            return Http::getJsonReponseSuccess($response, [$token], 'Sucesso', Http::OK);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), $error->getCode());

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }

    }

    public static function createSession($jwtData) {
        $_SESSION['user_id'] = $jwtData->id;
        $_SESSION['user_name'] = $jwtData->name;
        $_SESSION['user_email'] = $jwtData->email;
    }

    public function search(Request $request, Response $response, array $args) : Response {
        $app = $this->model;
        $params = $request->getParsedBody();

        try {
            if (empty($params)) {
                throw new SqlQueryException('Não foram encontrados currículos com estes parâmetros', Http::NOT_FOUND);
            }

            $curriculums = $app->search($params);

            return Http::getJsonReponseSuccess($response, $curriculums, 'Sucesso', Http::OK);
        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function view(Request $request, Response $response, array $args) : Response {
        $app = $this->model;
        $curriculumID = Security::filterInt($args['id']);

        try {
            if (empty($curriculumID)) {
                throw new SqlQueryException('Currículo não encontrado', Http::NOT_FOUND);
            }

            $curriculum = $app->view($curriculumID);

            return Http::getJsonReponseSuccess($response, $curriculum, 'Sucesso', Http::OK);
        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}