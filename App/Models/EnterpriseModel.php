<?php

namespace App\Models;

use App\DAO\EnterpriseDAO;
use InvalidArgumentException;

class EnterpriseModel {
    private string $name;
    private string $email;
    private string $password;

    private $DAO;

    public function __construct() {
        $this->DAO = new EnterpriseDAO();
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

}