<?php
namespace Crud\Controllers;

use Crud\Models\Record;
use Crud\Views\RecordView;

class RecordController
{
    public function index(): void
    {
        $model = new Record();
        $view = new RecordView();
        
        $records = $model->getAll();
        echo $view->render($records);
    }
}