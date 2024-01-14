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

        $user = $_SERVER['PHP_AUTH_USER'];
        $pass512 = $_SERVER['PHP_AUTH_PW'];

        return new HttpBasicAuthentication($userModel->getArrayPersonsAuth($user, $pass512));
    }
}