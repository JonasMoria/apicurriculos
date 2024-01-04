<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, UPDATE");
header('Content-Type: application/json; charset=utf-8');

require_once 'vendor/autoload.php';
require_once 'env.php';
require_once 'src/slimConfiguration.php';

date_default_timezone_set('America/Sao_Paulo');

require_once('routes/routes.php');