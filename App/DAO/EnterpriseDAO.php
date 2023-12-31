<?php

namespace App\DAO;

use App\Models\EnterpriseModel;
use App\Models\Security;
use InvalidArgumentException;

class EnterpriseDAO {
    private $database;
    const TABLE = 'enterprises';

    public function __construct() {
        $this->database = new Database();
    }

    public function executeInsert(EnterpriseModel $enterprise) {
        $dbase = $this->database;

        $name = $dbase->scapeString($enterprise->getName());
        $email = $dbase->scapeString($enterprise->getEmail());
        $pass = $dbase->scapeString($enterprise->getPassword());
        $pass = Security::convertToSha512($pass);

        if (self::getEnterpriseByEmail($email)) {
            throw new InvalidArgumentException('Empresa já cadastrada');
        }

        $query = "
            INSERT INTO " . self::TABLE . "
                (name, email, pass)
            VALUES
                ('" . $name . "', '" . $email . "', '" . $pass . "')
        ";

        return $dbase->executeQuery($query);
    }

    public function getEnterpriseByEmail($email) {
        $dbase = $this->database;

        $userEmail = $dbase->scapeString($email);

        $query = "
            SELECT
                E.id
            FROM
                " . self::TABLE . " E
            WHERE
                email = '" . $userEmail . "'
        ";

        return $dbase->fetchAssoc($query);
    }
}