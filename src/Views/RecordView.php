<?php
namespace Crud\Views;

class RecordView
{
    public static function render(array $records): string
    {
        $rows = '';
        foreach ($records as $r) {
            $rows .= '<tr><td>' . htmlspecialchars($r['id']) . '</td><td>' . htmlspecialchars($r['name']) . '</td></tr>';
        }
        
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>Название</th></tr></thead>
            <tbody>$rows</tbody>
        </table>
    </div>
</body>
</html>
HTML;
    }
}