<?php
require_once 'vendor/autoload.php'; // или ваши include

use Crud\Controllers\RecordController;

$controller = new RecordController();
$controller->handleRequest();