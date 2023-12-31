<?php

namespace App\Middlewares;

use Tuupola\Middleware\HttpBasicAuthentication;

class basicAuth {
    public static function authPerson() : HttpBasicAuthentication {
        return new HttpBasicAuthentication([
            'users' => [
                'root' => '123'
            ]
        ]);
    }
}