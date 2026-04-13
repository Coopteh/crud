<?php
namespace Crud\Controllers;
use Crud\Models\Record;
use Crud\Views\RecordView;

class RecordController
{
    private Record $model;
    private RecordView $view;

    public function __construct()
    {
        $this->model = new Record();
        $this->view = new RecordView();
    }

    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? 'index';
        $page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        switch ($action) {
            case 'insert':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->model->insert([
                        'name'        => $_POST['name'],
                        'description' => $_POST['description'] ?? '',
                        'image'       => $_POST['image'] ?? '',
                        'price'       => (float)$_POST['price']
                    ]);
                    $this->redirect();
                }
                echo $this->view->renderForm('insert');
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->model->update((int)$_POST['id'], [
                        'name'        => $_POST['name'],
                        'description' => $_POST['description'] ?? '',
                        'image'       => $_POST['image'] ?? '',
                        'price'       => (float)$_POST['price']
                    ]);
                    $this->redirect();
                }
                break;

            case 'edit':
                $record = $this->model->getById((int)$_GET['id']);
                if ($record) {
                    echo $this->view->renderForm('edit', $record);
                } else {
                    $this->redirect();
                }
                break;

            case 'delete':
                if (isset($_GET['id'])) {
                    $this->model->delete((int)$_GET['id']);
                }
                $this->redirect();
                break;

            case 'index':
            default:
                $limit  = 10;
                $offset = ($page - 1) * $limit;
                $total  = $this->model->countAll();
                $records = $this->model->getAll($limit, $offset);
                echo $this->view->renderIndex($records, $total, $page, $limit);
                break;
        }
    }

    private function redirect(): void
    {
        header('Location: index.php?action=index');
        exit;
    }
}