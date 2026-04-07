<?php

namespace Crud\Controllers;

use Crud\Models\Record;
use Crud\Views\RecordView;

class RecordController
{
    public function get()
    {
        $model = new Record();
        $data = $model->loadData();
        $html = RecordView::getTemplate($data);
        return $html;
    }
}
