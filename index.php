<?php
require_once __DIR__ . '/vendor/autoload.php';
use Crud\Controllers\RecordController;

echo (new RecordController())->index();