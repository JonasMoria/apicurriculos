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
        Security::validateName($name);

        $this->name = $name;
    }

    public function setEmail(string $email) {
        Security::validateEmail($email);

        $this->email = $email;
    }

    public function setPassword(string $password) {
        Security::validatePass($password);

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

    public function getArrayPersonsAuth(string $user, string $pass512) {
        $dao = $this->DAO;
        $user = $dao->getPersonsToAuth($user, $pass512);

        if (!empty($user)) {
            $_SESSION['user_id'] = $user['id'];
        }

        $arrayUser = [
            $user['email'] => $user['pass']
        ];

        $array['users'] = $arrayUser;

        return $array;
    }
}
