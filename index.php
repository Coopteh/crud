<?php

require_once("./vendor/autoload.php");
use Crud\Controllers\RecordController;

$controller = new RecordController();
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST')
    echo $controller->post();
else
    echo $controller->get();
