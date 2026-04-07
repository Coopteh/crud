<?php
namespace Crud\Views;

class RecordView
{
    public function render(array $records): string
    {
        $rows = '';
        foreach ($records as $r) {
            $rows .= "<tr><td>{$r['id']}</td><td>" . htmlspecialchars($r['name']) . "</td></tr>\n";
        }
        
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Список</h2>
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