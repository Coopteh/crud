<?php

namespace Crud\Views;

class RecordView
{
    public static function getTemplate($data): string
    {
        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
            <title>CRUD project</title>
        </head>
        <body>
        HTML;

        $html .= <<<TABLE
        <div class="container">
            <h3 class="mt-5">Данные о космических объектах</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Удалена</th>
                        <th scope="col">Наименование</th>
                        <th scope="col">Операции</th>
                    </tr>
                </thead>
            <tbody>
        TABLE;
        foreach ($data as $row) {
            $char_deleted = ($row['is_deleted']) ? '❌' : ' ';
            $html .= "<tr><td>{$row['id']}</td><td>$char_deleted</td><td>{$row['name']}</td>";
            $html .= '<td><a href="?action=edit&id=' . $row["id"] . '" class="btn btn-sm btn-secondary">Редактировать</a> ';
            $html .= '<a href="?action=delete&id=' . $row["id"] . '" class="btn btn-sm btn-secondary">Удалить\восстановить</a>';
            $html .= "</td></tr>";
        }
        $html .= "</tbody></table>";
        $html .= '<a href="?action=insert" class="btn btn-primary mt-3 btn-secondary">Добавить запись</a>';        
        $html .= "</div></body></html>";

        return $html;
    }

    public static function getForm(?array $rec): string
    {
        $nameAction = isset($rec) ? "Изменение" : "Добавление";
        $valueName= isset($rec) ? $rec['name'] : '';
        $id= isset($rec) ? $rec['id'] : '';
        $html = <<<FORM
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
            <title>CRUD project</title>
        </head>
        <body>
        <div class="container">
            <h3 class="mt-5">{$nameAction} данных</h3>        
            <form method="POST">
                <div class="mb-3">
                    <input type="hidden" name="id" value="{$id}">
                    <label for="name" class="form-label">Наименование</label>
                    <input type="text" class="form-control" id="name" 
                    name="name" placeholder="Введите наименование" value="{$valueName}">
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
        </body>
        </html>
        FORM;
        return $html;
    }
}
