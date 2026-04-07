<?php
namespace Crud\Controllers;

use Crud\Models\Record;
use Crud\Views\RecordView;

class RecordController
{
    public function index(): string
    {
        $records = (new Record())->getAll();
        return RecordView::render($records);
    }
}