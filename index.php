<?php

require_once __DIR__ . '/src/database.php';
require_once __DIR__ . '/src/Models/Record.php';
require_once __DIR__ . '/src/Controllers/RecordController.php';
require_once __DIR__ . '/src/Views/RecordView.php';

$controller = new RecordController();
$view = new RecordView();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'index':
    default:
        $records = $controller->index();
        echo $view->list($records);
        break;

    case 'show':
        $id = (int)($_GET['id'] ?? 0);
        echo $view->show($controller->show($id));
        break;

    case 'new':
        echo $view->form();
        break;

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store($_POST);
            header('Location: ?action=index');
            exit;
        }
        break;

    case 'edit':
        $id = (int)($_GET['id'] ?? 0);
        echo $view->form($controller->show($id));
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            $controller->update($id, $_POST);
            header('Location: ?action=index');
            exit;
        }
        break;

    case 'delete':
        $id = (int)($_GET['id'] ?? 0);
        $controller->destroy($id);
        header('Location: ?action=index');
        exit;
}
