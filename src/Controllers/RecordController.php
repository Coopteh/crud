<?php

namespace Crud\Controllers;

use Crud\Models\Record;
use Crud\Views\RecordView;

class RecordController
{
    public function get()
    {
        $model = new Record();
        if (isset($_GET['action'])) {
            if ($_GET['action'] =='delete') {
                $model->delete($_GET['id']);
            }
            if (($_GET['action'] =='edit') || ($_GET['action'] =='insert')) {
                $rec = null;
                if ($_GET['action'] =='edit')
                    $rec = $model->loadRecord($_GET['id']);
                $html = RecordView::getForm($rec);
                return $html;
            }
        }
        
        return $this->getAll();
    }

    public function getAll()
    {
        $model = new Record();
        $data = $model->loadData();
        $html = RecordView::getTemplate($data);
        return $html;
    }

    public function post()
    {
        $model = new Record();
        if (isset($_POST['id']) && !empty($_POST['id'])) {    
            $model->saveRecord($_POST['id']);
        } else {
            $model->saveRecord();
        }
        return $this->getAll();
    }
}
