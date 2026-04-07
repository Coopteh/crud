<?php

namespace App\Controllers;

use App\Models\Record;
use App\Views\RecordView;

class RecordController
{
    public function index(): void
    {
        // 1. Запрос данных у модели
        $model = new Record();
        $records = $model->getAll();

        // 2. Передача данных в представление и рендеринг
        $view = new RecordView();
        $view->render($records);
    }
}
