<?php
namespace Crud\Views;

class RecordView
{
    public static function renderTable(array $records, int $currentPage, int $totalPages): string
    {
        $rows = '';
        foreach ($records as $r) {
            $id = htmlspecialchars($r['id_product']);
            $name = htmlspecialchars($r['name']);
            $description = htmlspecialchars($r['description'] ?? '');
            $price = number_format((float)$r['price'], 2, '.', ' ');
            $created = htmlspecialchars($r['created'] ?? '');
            
            $rows .= "<tr>
                <td>$id</td>
                <td>$name</td>
                <td>$description</td>
                <td>$price ₽</td>
                <td>$created</td>
                <td>
                    <a href='?action=edit&id=$id&page=$currentPage' class='btn btn-sm btn-warning'>Редактировать</a>
                    <a href='?action=delete&id=$id&page=$currentPage' class='btn btn-sm btn-danger' onclick=\"return confirm('Удалить?')\">Удалить</a>
                </td>
            </tr>";
        }
        
        $pagination = self::renderPagination($currentPage, $totalPages);
        
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Товары</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <a href="?action=insert" class="btn btn-primary mb-3">Добавить товар</a>
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>Название</th><th>Описание</th><th>Цена</th><th>Создан</th><th>Действия</th></tr></thead>
            <tbody>$rows</tbody>
        </table>
        $pagination
        <a href="index.php" class="btn btn-secondary">На главную</a>
    </div>
</body>
</html>
HTML;
    }
    
    public static function renderForm(?array $record = null): string
    {
        $id = $record['id_product'] ?? '';
        $name = htmlspecialchars($record['name'] ?? '');
        $description = htmlspecialchars($record['description'] ?? '');
        $price = htmlspecialchars($record['price'] ?? '');
        $title = $id ? 'Редактировать' : 'Добавить';
        $action = $id ? 'update' : 'store';
        
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>$title</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <form method="POST" action="?action=$action">
            <input type="hidden" name="id" value="$id">
            <div class="mb-3">
                <label>Название *</label>
                <input type="text" name="name" class="form-control" value="$name" required>
            </div>
            <div class="mb-3">
                <label>Описание</label>
                <textarea name="description" class="form-control" rows="3">$description</textarea>
            </div>
            <div class="mb-3">
                <label>Цена *</label>
                <input type="number" name="price" class="form-control" value="$price" step="0.01" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="index.php" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</body>
</html>
HTML;
    }
    
    private static function renderPagination(int $current, int $total): string
    {
        if ($total <= 1) return '';
        
        $html = '<nav><ul class="pagination justify-content-center">';
        
        $html .= '<li class="page-item' . ($current === 1 ? ' disabled' : '') . '">';
        $html .= '<a class="page-link" href="?page=1">Первая</a></li>';
        
        $prev = max(1, $current - 1);
        $html .= '<li class="page-item' . ($current === 1 ? ' disabled' : '') . '">';
        $html .= '<a class="page-link" href="?page=' . $prev . '">Пред</a></li>';
        
        for ($i = 1; $i <= $total; $i++) {
            $active = ($i === $current) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
        
        $next = min($total, $current + 1);
        $html .= '<li class="page-item' . ($current === $total ? ' disabled' : '') . '">';
        $html .= '<a class="page-link" href="?page=' . $next . '">След</a></li>';
        
        $html .= '<li class="page-item' . ($current === $total ? ' disabled' : '') . '">';
        $html .= '<a class="page-link" href="?page=' . $total . '">Последняя</a></li>';
        
        $html .= '</ul></nav>';
        return $html;
    }
}