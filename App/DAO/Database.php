<?php

namespace App\DAO;

use Exception;

class Database {
    
    private static function connectionDB() {
        $host = getenv('DB_HOST');
        $database = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');

        $connect = mysqli_connect($host, $user, $pass, $database);
        if (!$connect) {
            throw new Exception('Não foi possível realizar esta ação, por favor, tente novamente mais tarde', 1);
        }

        return $connect;
    }

    public function executeQuery($query) {
        $connection = self::connectionDB();

        return mysqli_query($connection, $query);
    }

    public function fetchAssoc($query) {
        return mysqli_fetch_assoc(self::executeQuery($query));
    }

    public function fetchAll($query) {
        return mysqli_fetch_all(self::executeQuery($query));
    }

    public function scapeString($string) {
        return mysqli_real_escape_string(self::connectionDB(), $string);
    }
}
