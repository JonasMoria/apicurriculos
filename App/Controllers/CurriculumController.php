<?php

namespace App\Controllers;

use App\Models\CurriculumModel;
use App\Models\Http;
use Exception;
use InvalidArgumentException;
use Slim\Http\Request;
use Slim\Http\Response;

class CurriculumController {
    private $model;

    /**Máximo  de currículos por pessoa */
    const MAX_CURRICULUM = 3;

    public function __construct() {
        $this->model = new CurriculumModel();
    }

    public function newCurriculum(Request $request, Response $response, array $arg) : Response {
        $curriculum = $this->model;
        $params = $request->getParsedBody();

        try {

            $json = Http::obtainJsonSuccess('Currículo cadastrado com sucesso');

            $response->getBody()->write(json_encode($params));
            
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