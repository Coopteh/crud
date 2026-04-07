<?php
namespace Crud\Controllers;
use Crud\Model\Record;
use Crud\Views\RecordView;
class RecordController{
    public function get(): void{
        // 1. Обращение к модели
        $recordModel = new Record();
        $records = $recordModel->getAll();

        // 2. Обращение к представлению
        $view = new RecordView();
        $view->render($records);
    }
}