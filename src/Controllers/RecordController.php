<?php

namespace Crud\Controllers;

use Crud\Model\Record;use Crud\Views\RecordView;

class RecordController{    // Константы для настройки пагинации    
    private const ITEMS_PER_PAGE = 10;    
    private const ACTION_LIST = 'list';
    private Record $model;
    private RecordView $view;

    public function __construct()
    {
        $this->model = new Record();
        $this->view = new RecordView();
    }

    public function handleRequest(): void
    {
        // Получаем действие из запроса
        $action = $_GET['action'] ?? self::ACTION_LIST;

        switch ($action) {
            case self::ACTION_LIST:
            default:
                // --- ЛОГИКА ПАГИНАЦИИ ---
                $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
                $offset = ($currentPage - 1) * self::ITEMS_PER_PAGE;

                // Получаем данные для текущей страницы
                $dataForPage = $this->model->getAll(self::ITEMS_PER_PAGE, $offset);

                // Получаем общее количество записей для расчёта страниц
                $totalRecordsCount = $this->model->getTotalCount();
                $totalPages = $totalRecordsCount > 0 ? ceil($totalRecordsCount / self::ITEMS_PER_PAGE) : 1;

                // Подготавливаем данные для рендера
                $data = [
                    'data' => $dataForPage,
                    'current_page' => $currentPage,
                    'total_pages' => $totalPages,
                ];

                $this->view->render($data, self::ACTION_LIST);
                break;

            case 'insert':
                // Для страницы вставки пагинация не нужна, передаём пустой массив
                $this->view->render([], 'insert');
                break;

            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $name = trim($_POST['name'] ?? '');
                    if (!empty($name)) {
                        $this->model->insert($name);
                    }
                }
                // После добавления возвращаемся на первую страницу списка
                header('Location: ?action=' . self::ACTION_LIST);
                exit;

            case 'edit':
                $id_product = (int)($_GET['id_product'] ?? 0);
                $record = $id_product > 0 ? $this->model->getByid_product($id_product) : null;

                // Передаём только данные для редактирования, список записей не нужен
                $data = ['edit_data' => $record];
                $this->view->render($data, 'edit');
                break;

            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id_product = (int)($_POST['id_product'] ?? 0);
                    $name = trim($_POST['name'] ?? '');
                    if ($id_product > 0 && !empty($name)) {
                        $this->model->update($id_product, $name);
                    }
                }

                // Сохраняем текущую страницу при редиректе после обновления
                $redirectPage = isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '';
                header('Location: ?action=' . self::ACTION_LIST . $redirectPage);
                exit;

            case 'toggle_delete':
                $id_product = (int)($_GET['id_product'] ?? 0);
                if ($id_product > 0) {
                    $this->model->toggleDeleted($id_product);
                }

                // Сохраняем текущую страницу при редиректе после удаления
                $redirectPage = isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '';
                header('Location: ?action=' . self::ACTION_LIST . $redirectPage);
                exit;

            case 'delete':
                // Если нужно физическое удаление вместо soft delete:
                $id_product = (int)($_GET['id_product'] ?? 0);
                if ($id_product > 0) {
                    $this->model->delete($id_product);
                }

                // Сохраняем текущую страницу при редиректе после удаления
                $redirectPage = isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '';
                header('Location: ?action=' . self::ACTION_LIST . $redirectPage);
                exit;
        }
    }
}