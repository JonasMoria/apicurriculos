<?php

namespace App\DAO;

use App\Models\Security;
use App\Models\UserModel;

use App\Exceptions\InvalidParamException;
use App\Exceptions\SqlQueryException;

class UserDAO {
    private $database;
    const TABLE = 'users';

    public function __construct() {
        $this->database = new Database();
    }

    public function executeInsert(UserModel $user) {
        $dbase = $this->database;

        $name = $dbase->scapeString($user->getName());
        $email = $dbase->scapeString($user->getEmail());
        $pass = $dbase->scapeString($user->getPassword());
        $pass = Security::convertToSha512($pass);

        if (self::getUserByEmail($email)) {
            throw new SqlQueryException('Usuário já cadastrado');
        }

        $query = "
            INSERT INTO " . self::TABLE . "
                (name, email, pass)
            VALUES
                ('" . $name . "', '" . $email . "', '" . $pass . "')
        ";

        return $dbase->executeQuery($query);
    }

    public function getUserByEmail($email) {
        $dbase = $this->database;

        $userEmail = $dbase->scapeString($email);

        $query = "
            SELECT
                U.id
            FROM
                " . self::TABLE . " U
            WHERE
                email = '" . $userEmail . "'
        ";

        return $dbase->fetchAssoc($query);
    }

    public function getAuthUser(string $user, string $pass512) {
        $dbase = $this->database;

        $userDB = $dbase->scapeString($user);
        $passDB = $dbase->scapeString($pass512);

        $query = "
            SELECT
                U.id,
                U.email,
                U.pass,
                U.status
            FROM
                " . self::TABLE . " U
            WHERE
                U.email = '" . $userDB . "'
                    AND U.pass = '" . $passDB . "'
            LIMIT 1
        ";

        return $dbase->fetchAssoc($query);
    }
}