<?php
namespace Crud\Views;

class RecordView
{
    public static function renderTable(array $records, int $currentPage, int $totalPages): string
    {
        $rows = '';
        foreach ($records as $r) {
            $id = htmlspecialchars($r['id']);
            $name = htmlspecialchars($r['name']);
            $deleted = (int)$r['deleted'];
            
            // ✅ Галочка или ❌ Крестик
            $status = $deleted ? '❌' : '✅';
            $statusTitle = $deleted ? 'Восстановить' : 'Удалить';
            $rowClass = $deleted ? 'table-danger' : '';
            
            $rows .= "<tr class='$rowClass'>
                <td>$id</td>
                <td>$name</td>
                <td>$status</td>
                <td>
                    <a href='?action=edit&id=$id&page=$currentPage' class='btn btn-sm btn-info text-white'>Редактировать</a>
                    <a href='?action=toggle&id=$id&page=$currentPage' class='btn btn-sm btn-outline-danger' title='$statusTitle'>🗑️</a>
                </td>
            </tr>";
        }
        
        $pagination = self::renderPagination($currentPage, $totalPages);
        
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="p-4">
    <div class="container">
        <a href="?action=insert" class="btn btn-success mb-3">➕ Добавить запись</a>
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>Название</th><th>Статус</th><th>Действия</th></tr></thead>
            <tbody>$rows</tbody>
        </table>
        $pagination
        <a href="index.php" class="btn btn-secondary">⬅ На главную</a>
    </div>
</body>
</html>
HTML;
    }
    
    public static function renderForm(?array $record = null): string
    {
        $id = $record['id'] ?? '';
        $name = htmlspecialchars($record['name'] ?? '');
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
                <label>Название</label>
                <input type="text" name="name" class="form-control" value="$name" required>
            </div>
            <button type="submit" class="btn btn-success">💾 Сохранить</button>
            <a href="index.php" class="btn btn-outline-secondary">✖ Отмена</a>
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
        $html .= '<a class="page-link" href="?page=1"><i class="bi bi-chevron-double-left"></i></a></li>';
        
        $prev = max(1, $current - 1);
        $html .= '<li class="page-item' . ($current === 1 ? ' disabled' : '') . '">';
        $html .= '<a class="page-link" href="?page=' . $prev . '"><i class="bi bi-chevron-left"></i></a></li>';
        
        for ($i = 1; $i <= $total; $i++) {
            $active = ($i === $current) ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
        
        $next = min($total, $current + 1);
        $html .= '<li class="page-item' . ($current === $total ? ' disabled' : '') . '">';
        $html .= '<a class="page-link" href="?page=' . $next . '"><i class="bi bi-chevron-right"></i></a></li>';
        
        $html .= '<li class="page-item' . ($current === $total ? ' disabled' : '') . '">';
        $html .= '<a class="page-link" href="?page=' . $total . '"><i class="bi bi-chevron-double-right"></i></a></li>';
        
        $html .= '</ul></nav>';
        return $html;
    }
}