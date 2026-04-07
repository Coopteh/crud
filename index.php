<?php

require_once("./vendor/autoload.php");
use Crud\Controllers\RecordController;

$controller = new RecordController();
echo $controller->get();
