<?php

namespace App\Controllers;

use App\Models\Http;
use App\Models\Security;
use App\Models\UserModel;
use Slim\Http\Request;
use Slim\Http\Response;

use App\Exceptions\InvalidParamException;
use App\Exceptions\SqlQueryException;

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function disableAccount(Request $request, Response $response, array $arg) : Response {
        $user = $this->model;
        $params = $request->getParsedBody();
        $userID = $_SESSION['user_id'];

        try {
            if (empty($userID)) {
                throw new InvalidParamException('Usuário não identificado');
            }

            $user->disableAccount($userID);

            return Http::getJsonReponseSuccess($response, [], 'Conta Desativada Com Sucesso', Http::CREATED);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function updatePerfil(Request $request, Response $response, array $arg) : Response {
        $user = $this->model;
        $params = $request->getParsedBody();
        $userID = $_SESSION['user_id'];

        try {
            if (empty($params)) {
                throw new InvalidParamException('Não foram identificados dados a serem alterados');
            }
            if (empty($userID)) {
                throw new InvalidParamException('Usuário não identificado');
            }

            $fieldsToUpdate = $user->makeArrayUpdatePerfil($params);

            $user->updatePerfil($userID, $fieldsToUpdate);

            return Http::getJsonReponseSuccess($response, [], 'Usuário Alterado Com Sucesso', Http::CREATED);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function InsertNewUser(Request $request, Response $response, array $arg) : Response {
        $user = $this->model;
        $params = $request->getParsedBody();

        try {

            $user->setName(Security::sanitizeString($params['name']));
            $user->setEmail(Security::sanitizeEmail($params['email']));
            $user->setPassword($params['password']);

            $user->insert($user);
            return Http::getJsonReponseSuccess($response, [], 'Usuário Cadastrado Com Sucesso', Http::CREATED);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }

    public function viewPerfil(Request $request, Response $response, array $arg) : Response {
        $user = $this->model;
        $params = $request->getParsedBody();
        $userID = $_SESSION['user_id'];

        try {
            $userData = $user->getPerfil($userID);

            return Http::getJsonReponseSuccess($response, $userData, 'Sucesso', Http::OK);

        } catch (InvalidParamException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::BAD_REQUEST);

        } catch (SqlQueryException $error) {
            return Http::getJsonReponseError($response, $error->getMessage(), Http::NOT_FOUND);

        } catch (\Exception $error) {
            return Http::getJsonResponseErrorServer($response, $error);
        }
    }
}