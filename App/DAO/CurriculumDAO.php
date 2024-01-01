<?php

namespace App\DAO;

class CurriculumDAO {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }
}