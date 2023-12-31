<?php

namespace App\Middlewares;

use App\Models\UserModel;
use Tuupola\Middleware\HttpBasicAuthentication;

class basicAuth {
    /**
     * Método para autenticar usuários
     */
    public static function authPerson() : HttpBasicAuthentication {
        $userModel = new UserModel();

        return new HttpBasicAuthentication($userModel->getArrayPersonsAuth());
    }
}