<?php

namespace Crud\Views;

class RecordView
{
    public function render(array $records): string
    {
        $html = <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список записей</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1 class="mb-4">Записи из базы данных</h1>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Название</th>
            </tr>
        </thead>
        <tbody>
HTML;

        foreach ($records as $record) {
            $html .= "<tr><td>{$record['id']}</td><td>" .
                     htmlspecialchars($record['name']) . "</td></tr>\n";
        }

        $html .= <<<HTML
        </tbody>
    </table>
</body>
</html>
HTML;
        return $html;
    }
}
