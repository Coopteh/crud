<?php

namespace App\Controllers;

use App\Models\Record;
use App\Models\ValidateData;
use App\Views\RecordView;

class RecordController
{
    private Record     $model;
    private RecordView $view;
    private ValidateData $validator;

    public function __construct()
    {
        $this->model = new Record();
        $this->view  = new RecordView();
        $this->validator = new ValidateData();
    }

    public function handleRequest(): void
    {
        // Запускаем сессию для flash-сообщений
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'insert':
                $this->showForm();
                break;
            case 'store':
                $this->store();
                break;
            case 'edit':
                $this->showForm((int)($_GET['id'] ?? 0));
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                $this->index();
        }
    }

    private function index(): void
    {
        $records = $this->model->getAll();
        $message = $_SESSION['flash_message'] ?? null;
        $type    = $_SESSION['flash_type']    ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);

        $this->view->renderList($records, $message, $type);
    }

    private function showForm(?int $id = null): void
    {
        $record = $id ? $this->model->getById($id) : null;

        // Если редактируем несуществующую запись
        if ($id && !$record) {
            $this->setFlash('Запись не найдена.', 'danger');
            header('Location: ?action=index');
            exit;
        }

        $fields = $this->model->getFormFields();
        $this->view->renderForm($record, $fields, $id);
    }

    private function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=index');
            exit;
        }
        $data = $this->sanitizeInput($_POST);
        // Валидация
        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            $this->setFlash(implode(' ', $errors), 'danger');
            header('Location: ?action=insert');
            exit;
        }

        if ($this->model->insert($data)) {
            $this->setFlash('Запись успешно добавлена.', 'success');
        } else {
            $this->setFlash('Ошибка при добавлении записи.', 'danger');
        }

        header('Location: ?action=index');
        exit;
}
        // $data = $this->sanitizeInput($_POST);

        // if ($this->model->insert($data)) {
        //     $this->setFlash('Запись успешно добавлена.', 'success');
        // } else {
        //     $this->setFlash('Ошибка при добавлении записи.', 'danger');
        // }

        // header('Location: ?action=index');
        // exit;
        //}
    private function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
            header('Location: ?action=index');
            exit;
        }

        $id   = (int)$_POST['id'];
        $data = $this->sanitizeInput($_POST);

        if ($this->model->update($id, $data)) {
            $this->setFlash('Запись успешно обновлена.', 'success');
        } else {
            $this->setFlash('Ошибка при обновлении записи.', 'danger');
        }

        header('Location: ?action=index');
        exit;
    }

    private function delete(): void
    {
        if (empty($_GET['id'])) {
            header('Location: ?action=index');
            exit;
        }

        $id = (int)$_GET['id'];

        if ($this->model->delete($id)) {
            $this->setFlash('Запись успешно удалена.', 'success');
        } else {
            $this->setFlash('Ошибка при удалении записи.', 'danger');
        }

        header('Location: ?action=index');
        exit;
    }

    private function setFlash(string $message, string $type = 'info'): void
    {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type']    = $type;
    }

    private function sanitizeInput(array $input): array
    {
        $sanitized = [];
        foreach ($input as $key => $value) {
            if ($key === 'id' || $key === 'is_deleted') {
                continue;
            }
            $sanitized[$key] = is_string($value) ? trim($value) : $value;
        }
        return $sanitized;
    }
}