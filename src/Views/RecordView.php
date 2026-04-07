<?php

class RecordView
{
    public function list(array $records): string
    {
        $html = '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1>Записи</h1>
    <a href="?action=new" class="btn btn-primary mb-3">Добавить</a>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>';

        if (empty($records)) {
            $html .= '<tr><td colspan="3" class="text-center">Нет записей</td></tr>';
        } else {
            foreach ($records as $r) {
                $html .= '<tr>';
                $html .= '<td>' . $r->id . '</td>';
                $html .= '<td>' . htmlspecialchars($r->name) . '</td>';
                $html .= '<td>';
                $html .= '<a href="?action=show&id=' . $r->id . '" class="btn btn-sm btn-info">Просмотр</a> ';
                $html .= '<a href="?action=edit&id=' . $r->id . '" class="btn btn-sm btn-warning">Редактировать</a> ';
                $html .= '<a href="?action=delete&id=' . $r->id . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Удалить?\')">Удалить</a>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</tbody>
    </table>
</body>
</html>';

        return $html;
    }

    public function show(?Record $record): string
    {
        if (!$record) {
            return $this->error('Не найдено.');
        }

        return '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1>Просмотр записи</h1>
    <a href="?action=index" class="btn btn-secondary mb-3">Назад</a>
    <table class="table table-bordered">
        <tr><th>ID</th><td>' . $record->id . '</td></tr>
        <tr><th>Название</th><td>' . htmlspecialchars($record->name) . '</td></tr>
    </table>
</body>
</html>';
    }

    public function form(?Record $record = null): string
    {
        $id = $record ? $record->id : '';
        $name = $record ? htmlspecialchars($record->name) : '';
        $btn = $id ? 'Обновить' : 'Создать';
        $title = $id ? 'Редактирование' : 'Создание';

        return "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$title</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='container mt-4'>
    <h1>$title</h1>
    <a href='?action=index' class='btn btn-secondary mb-3'>Назад</a>
    <form method='post'>
        <div class='mb-3'>
            <label class='form-label'>Название</label>
            <input type='text' name='name' value='$name' class='form-control' required>
        </div>
        <button type='submit' class='btn btn-primary'>$btn</button>
    </form>
</body>
</html>";
    }

    private function error(string $message): string
    {
        return "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='container mt-4'>
    <div class='alert alert-danger'>$message</div>
    <a href='?action=index' class='btn btn-secondary'>Назад</a>
</body>
</html>";
    }
}
