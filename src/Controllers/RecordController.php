<?php

namespace Crud\Controllers;

use Crud\Models\Record;
use Crud\Views\RecordView;

class RecordController
{
    public function index(): void
    {
        $model = new Record();
        $records = $model->getAll();

        $view = new RecordView();
        echo $view->render($records);
    }
}
