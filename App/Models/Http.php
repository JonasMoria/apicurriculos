<?php

namespace App\Models;
use Slim\Http\Response;

class Http {
    const OK = 200;
    const CREATED = 201;

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;

    const SERVER_ERROR = 500;

    public static function getJsonReponseSuccess(Response $response, array $payload = [], string $message = '', int $httpCode = 0) : Response {
        return $response->withJson([
            'message' => $message,
            'status' => $httpCode,
            'data' => $payload,
        ], $httpCode);
    }

    public static function getJsonReponseError(Response $response, string $message, int $httpCode) {
        return $response->withJson([
            'message' => $message,
            'status' => $httpCode,
        ], $httpCode);
    }

    public static function getJsonResponseErrorServer(Response $response, $error) {
        //Código de log
        return $response->withJson([
            'message' => 'Não foi possível atendar a solicitação no momento, por favor, aguarde uns instantes.',
            'status' => Http::SERVER_ERROR,
        ], Http::SERVER_ERROR);
    }
}