<?php
require __DIR__ . '/vendor/autoload.php';

$controller = new Crud\Controllers\RecordController();
$controller->index();