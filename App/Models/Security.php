<?php

namespace App\Models;

use DateTime;
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

    public static function validateURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function validateName(string $name) {
        if (empty($name)) {
            throw new InvalidArgumentException('Nome não pode ser vazio!');
        }
        if (strlen($name) > 256) {
            throw new InvalidArgumentException('Nome deve conter até 256 caracteres!');
        }
    }

    public static function removeDoubleSpace($string) {
        return preg_replace('/\\s\\s+/', ' ', $string);
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
        if (!self::validatePassword($password)) {
            throw new InvalidArgumentException('Senha Inválida. Verifique se: Tem mais de 6 caracteres, letras maiúsculas e minúsculas e números');
        }
    }

    public static function validateCity(string $city) {
        if (strlen($city) < 3 || strlen($city) > 256) {
            throw new InvalidArgumentException('Campo Cidade do usuário inválido');
        }
    }

    public static function validateUF(string $UF) {
        if (strlen($UF) != 2) {
            throw new InvalidArgumentException('Campo UF do usuário inválido');
        }
    }

    public static function validatePersonName(string $name) {
        $regex = "/^[A-ZÀ-Ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-ZÀ-Ÿ][A-zÀ-ÿ']+$/";
    
        if (!preg_match($regex, $name)) {
            throw new InvalidArgumentException('Campo Nome do usuário deve ser composto por no mínimo nome e sobrenome');
        }
    }

    public static function validatePersonDescription(string $description) {
        if (strlen($description) > 300) {
            throw new InvalidArgumentException('Campo Descrição do usuário deve ser composto de no máximo 300 caracteres');
        }
    }

    public static function fixName($name) {
        return mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
    }

    public static function formatDate($date) {
        return new DateTime($date);
    }

    public static function validateDateEmpty(Datetime $date, string $field) {
        $dateNow = new DateTime();

        if ($dateNow < $date) {
            throw new InvalidArgumentException('Campo ' . htmlspecialchars($field) .  ' inválido');
        }
    }

    public static function validateDate(DateTime $date) {
        $dateNow = new DateTime();

        if ($dateNow < $date) {
            throw new InvalidArgumentException('Campo Data de Nascimento do usuário inválida');
        }
    }

    public static function validatePhone(string $phone) {
        if (!is_numeric($phone)) {
            throw new InvalidArgumentException('Campo Telefone do usuário inválido');
        }

        if (strlen($phone) != 13) {
            if (strlen($phone) != 12) {
                throw new InvalidArgumentException('Campo Telefone do usuário inválido');
            }
        }
    }

    public static function validateLink(string $link, string $fieldName = '') {
        if (!self::validateURL($link)) {
            if ($fieldName != '') {
                throw new InvalidArgumentException('Campo redes sociais - ' . htmlspecialchars($fieldName) . ' inválido');
            } else {
                throw new InvalidArgumentException('Link inválido');
            }
        }
    }

    public static function validateEmpty($field, $fieldName) {
        if (empty($field)) {
            throw new InvalidArgumentException('Campo ' . htmlspecialchars($fieldName) . ' inválido');
        }
    }
}