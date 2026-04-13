<?php
namespace Crud\Views;

class RecordView
{
    private function getHead(): string
    {
        return '<!DOCTYPE html>
    <html lang="ru">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление продукцией</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    :root { --bg: #fafafa; --text: #1a1a1a; --muted: #6c757d; --border: #dee2e6; --hover: #f1f3f5; --active: #212529; }
    body { background-color: var(--bg); color: var(--text); font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
    .container { max-width: 960px; }
    h1 { font-size: 1.4rem; font-weight: 600; letter-spacing: -0.02em; margin-bottom: 1.5rem; }
    .table { font-size: 0.85rem; margin-bottom: 0; border-color: var(--border); }
    .table th { font-weight: 500; color: var(--muted); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.06em; border-bottom: 1px solid var(--border); padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .table td { vertical-align: middle; border-color: var(--border); }
    .table-hover tbody tr:hover { background-color: var(--hover); }
    .btn { font-size: 0.8rem; font-weight: 500; border-radius: 2px; padding: 0.25rem 0.75rem; border-width: 1px; transition: all 0.15s ease; }
    .btn-outline-dark { border-color: var(--border); color: #495057; background: transparent; }
    .btn-outline-dark:hover { background-color: var(--active); border-color: var(--active); color: #fff; }
    .btn-outline-secondary { border-color: var(--border); color: #495057; background: transparent; }
    .btn-outline-secondary:hover { background-color: var(--hover); border-color: #adb5bd; }
    .form-control, .form-select { border-radius: 2px; font-size: 0.9rem; padding: 0.5rem 0.75rem; border-color: var(--border); }
    .form-control:focus { border-color: #adb5bd; box-shadow: none; background-color: #fff; }
    .form-label { color: var(--muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; }
    .pagination .page-link { color: #495057; border: none; padding: 0.4rem 0.9rem; font-size: 0.85rem; border-radius: 2px; margin: 0 2px; background: transparent; }
    .pagination .page-link:hover { background-color: var(--hover); color: var(--text); }
    .pagination .active .page-link { background-color: var(--active); color: #fff; }
    .pagination .disabled .page-link { color: #ced4da; pointer-events: none; background: transparent; }
    .card { border: 1px solid var(--border); border-radius: 3px; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
    .text-truncate { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    </style>
    </head>
    <body>';
    }

    public function renderIndex(array $records, int $total, int $currentPage, int $limit): string
    {
        $totalPages = ceil($total / $limit);
        $html = $this->getHead();
        $html .= '<div class="container py-5">';
        $html .= '<div class="d-flex justify-content-between align-items-center mb-4">';
        $html .= '<h1 class="mb-0">Продукция</h1>';
        $html .= '<a href="?action=insert" class="btn btn-outline-dark">Добавить товар</a>';
        $html .= '</div>';
     $html .= '<div class="card">';
        $html .= '<table class="table table-hover mb-0">';
        $html .= '<thead><tr>
            <th width="5%">ID</th>
            <th width="20%">Название</th>
            <th>Описание</th>
            <th width="10%">Изображение</th>
            <th width="10%">Цена</th>
            <th width="20%" class="text-end">Действия</th>
        </tr></thead>';
        $html .= '<tbody>';
    if (empty($records)) {
            $html .= '<tr><td colspan="6" class="text-center py-4" style="color: var(--muted);">Записи отсутствуют</td></tr>';
        } else {
            foreach ($records as $row) {
                $html .= '<tr>';
                $html .= '<td class="text-muted">' . (int)$row['id_product'] . '</td>';
                $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
                $html .= '<td class="text-truncate">' . htmlspecialchars($row['description'] ?? '—') . '</td>';
                $img = $row['image'] ?? '';
                $html .= '<td>' . ($img ? '<span class="text-muted fst-italic">' . htmlspecialchars($img) . '</span>' : '—') . '</td>';
                $html .= '<td><strong>' . number_format((float)$row['price'], 2, '.', ' ') . ' ₽</strong></td>';
                $html .= '<td class="text-end">';
                $html .= '<a href="?action=edit&id=' . $row['id_product'] . '" class="btn btn-outline-secondary me-1">Ред.</a>';
                $html .= '<a href="?action=delete&id=' . $row['id_product'] . '" class="btn btn-outline-dark" onclick="return confirm(\'Удалить запись?\');">Удалить</a>';
                $html .= '</td></tr>';
            }
        }
        $html .= '</tbody></table></div>';
       $html .= $this->renderPagination($totalPages, $currentPage);
        $html .= '</div></body></html>';
        return $html;
    }

    private function renderPagination(int $totalPages, int $currentPage): string
    {
        if ($totalPages <= 1) return '';
    $html = '<nav class="mt-4"><ul class="pagination justify-content-center">';
        $html .= $currentPage > 1
            ? '<li class="page-item"><a class="page-link" href="?action=index&page=1">Первая</a></li>
               <li class="page-item"><a class="page-link" href="?action=index&page=' . ($currentPage - 1) . '">Назад</a></li>'
            : '<li class="page-item disabled"><span class="page-link">Первая</span></li>
               <li class="page-item disabled"><span class="page-link">Назад</span></li>';
            for ($i = 1; $i <= $totalPages; $i++) {
            $active = ($i === $currentPage) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="?action=index&page=' . $i . '">' . $i . '</a></li>';
        }
            $html .= $currentPage < $totalPages
            ? '<li class="page-item"><a class="page-link" href="?action=index&page=' . ($currentPage + 1) . '">Вперёд</a></li>
               <li class="page-item"><a class="page-link" href="?action=index&page=' . $totalPages . '">Последняя</a></li>'
            : '<li class="page-item disabled"><span class="page-link">Вперёд</span></li>
               <li class="page-item disabled"><span class="page-link">Последняя</span></li>';
            $html .= '</ul></nav>';
        return $html;
    }

    public function renderForm(string $mode, array $record = []): string
    {
        $isEdit = ($mode === 'edit');
        $id     = $record['id_product'] ?? '';
        $name   = $record['name'] ?? '';
        $desc   = $record['description'] ?? '';
        $image  = $record['image'] ?? '';
        $price  = $record['price'] ?? '';
        $title  = $isEdit ? 'Редактирование товара' : 'Новый товар';
        $action = $isEdit ? '?action=update' : '?action=insert';

        $html = $this->getHead();
        $html .= '<div class="container py-5" style="max-width: 600px;">';
        $html .= '<h1>' . $title . '</h1>';
        $html .= '<div class="card p-4">';
        $html .= '<form method="POST" action="' . $action . '">';
        if ($isEdit) {
            $html .= '<input type="hidden" name="id" value="' . (int)$id . '">';
        }
        $html .= '<div class="mb-3">
            <label for="name" class="form-label">Название *</label>
            <input type="text" class="form-control" id="name" name="name" value="' . htmlspecialchars($name) . '" required autofocus>
        </div>';
        $html .= '<div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" rows="3">' . htmlspecialchars($desc) . '</textarea>
        </div>';
        $html .= '<div class="mb-3">
            <label for="image" class="form-label">Имя файла изображения</label>
            <input type="text" class="form-control" id="image" name="image" value="' . htmlspecialchars($image) . '" placeholder="Например: product.jpg">
        </div>';
        $html .= '<div class="mb-3">
            <label for="price" class="form-label">Цена *</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="' . htmlspecialchars($price) . '" required>
        </div>';
        $html .= '<div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-outline-dark">Сохранить</button>
            <a href="?action=index" class="btn btn-outline-secondary">Отмена</a>
        </div>';
        $html .= '</form></div></div></body></html>';
        return $html;
    }
}