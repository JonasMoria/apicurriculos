<?php

namespace App\Models;

use InvalidArgumentException;

class Security {
    public static function sanitizeString($string) {
        return filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    public static function sanitizeEmail($email) {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    public static function validatePassword($password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{6,}$/', $password);
    }

    public static function convertToSha512($string) {
        return hash('sha512', $string);
    }

    public static function validateName(string $name) {
        if (empty($name)) {
            throw new InvalidArgumentException('Nome não pode ser vazio!');
        }
        if (strlen($name) > 256) {
            throw new InvalidArgumentException('Nome deve conter até 256 caracteres!');
        }
    }

    public static function validateEmail(string $email) {
        if (empty($email)) {
            throw new InvalidArgumentException('Email não pode ser vazio!');
        }
        if (strlen($email) > 256) {
            throw new InvalidArgumentException('Email deve conter até 256 caracteres!');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email inválido, verifique os dados e tente novamente!');
        }
    }

    public static function validatePass(string $password) {
        if (empty($password)) {
            throw new InvalidArgumentException('Senha não pode ser vazia!');
        }
        if (!Security::validatePassword($password)) {
            throw new InvalidArgumentException('Senha Inválida. Verifique se: Tem mais de 6 caracteres, letras maiúsculas e minúsculas e números');
        }
    }
}