<?php

namespace App\Models;

use App\DAO\UserDAO;
use InvalidArgumentException;

class UserModel {
    private string $name;
    private string $email;
    private string $password;

    private $DAO;

    public function __construct() {
        $this->DAO = new UserDAO();
    }

    public function setName(string $name) {
        self::validateName($name);

        $this->name = $name;
    }

    public function setEmail(string $email) {
        self::validateEmail($email);

        $this->email = $email;
    }

    public function setPassword(string $password) {
        self::validatePass($password);

        $this->password = $password;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function insert(self $user) {
        $dao = $this->DAO;

        if (!$dao->executeInsert($user)) {
            throw new InvalidArgumentException('Não foi possível realizar o cadastro, por favor, tente novamente mais tarde');
        }
    }

    private function validateName(string $name) {
        if (empty($name)) {
            throw new InvalidArgumentException('Nome não pode ser vazio!');
        }
        if (strlen($name) > 256) {
            throw new InvalidArgumentException('Nome deve conter até 256 caracteres!');
        }
    }

    private function validateEmail(string $email) {
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

    private function validatePass(string $password) {
        if (empty($password)) {
            throw new InvalidArgumentException('Senha não pode ser vazia!');
        }
        if (!Security::validatePassword($password)) {
            throw new InvalidArgumentException('Senha Inválida. Verifique se: Tem mais de 6 caracteres, letras maiúsculas e minúsculas e números');
        }
    }
}
