<?php
namespace Crud\Controllers;

use Crud\Models\Record;
use Crud\Views\RecordView;

class RecordController
{
    private Record $model;
    
    public function __construct()
    {
        $this->model = new Record();
    }
    
    public function handle(): string
    {
        $action = $_GET['action'] ?? 'index';
        
        switch ($action) {
            case 'insert':
                return RecordView::renderForm();
            
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
                    $this->model->create($_POST['name']);
                }
                header('Location: index.php');
                exit;
            
            case 'edit':
                $record = $this->model->getById((int)($_GET['id'] ?? 0));
                return RecordView::renderForm($record);
            
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id']) && !empty($_POST['name'])) {
                    $this->model->update((int)$_POST['id'], $_POST['name']);
                }
                header('Location: index.php');
                exit;
            
            case 'toggle':
                if (!empty($_GET['id'])) {
                    $this->model->toggleDelete((int)$_GET['id']);
                }
                header('Location: index.php');
                exit;
            
            default:
                $page = max(1, (int)($_GET['page'] ?? 1));
                $perPage = 10;
                $records = $this->model->getAll($page, $perPage);
                $total = $this->model->getCount();
                $totalPages = (int)ceil($total / $perPage);
                return RecordView::renderTable($records, $page, $totalPages);
        }
    }
}