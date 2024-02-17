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
        return mysqli_fetch_all(self::executeQuery($query), MYSQLI_ASSOC);
    }

    public function scapeString($string) {
        return mysqli_real_escape_string(self::connectionDB(), $string);
    }

    public function getLastInsertId($query) {
        $connection = self::connectionDB();
        mysqli_query($connection, $query);

        return mysqli_insert_id($connection);
    }

    public function getAffectedRows($query) {
        $connection = self::connectionDB();
        mysqli_query($connection, $query);

        return mysqli_affected_rows($connection);
    }

    public function insertWithArray(string $table, array $array) {
        $fields =  [];
        $values = [];

        foreach ($array as $column => $value) {
            array_push($fields, self::scapeString("`{$column}`"));
            array_push($values, "'" . self::scapeString($value) . "'");
        }
        
        $query = "
            INSERT INTO " . self::scapeString($table) . "
                (" . implode(',', $fields) . ")
            VALUES
                (" .implode(',', $values) . ")
        ";

        return self::getLastInsertId($query);
    }

    public function update($table, $fields, $where) {
        $query = "
            UPDATE " . self::scapeString($table) . "
            SET
                " . self::arrayToQueryString($fields) . "
            WHERE
                " . self::arrayToQueryString($where, true) . "
        ";

        return self::getAffectedRows($query);
    }

    public function delete($table, $where, $limit) {
        $query = "
            DELETE FROM " . self::scapeString($table) . "
            WHERE
                " . self::arrayToQueryString($where) . "
            LIMIT " . self::scapeString($limit) . "
        ";

        return self::getAffectedRows($query);
    }

    private static function arrayToQueryString(array $data, $isWhereField = false) {
        $dbase = new Database();

        $queryString = '';

        $total = count($data) - 1;
        $count = 0;

        foreach ($data as $field => $value) {
            if ($isWhereField) {
                $separator = ($count < $total ? ' AND ' : '');
            } else {
                $separator = ($count < $total ? ', ' : '');
            }

            $field = $dbase->scapeString($field);
            if ($value == 'NOW()' || $value == 'CURRENT_TIMESTAMP()') {
                $value = $dbase->scapeString($value);
            } else {
                $value = "'" . $dbase->scapeString($value) . "'";
            }

            $queryString .=  $field . ' = ' .  $value  . $separator;
            $count++;
        }

        return $queryString;
    }
}
