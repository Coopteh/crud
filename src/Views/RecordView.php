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
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Наименование</th>
                    </tr>
                </thead>
            <tbody>
        TABLE;
        foreach ($data as $row) {
            $html .= "<tr><td>{$row['id']}</td><td>{$row['name']}</td></tr>";
        }
        $html .= "</tbody></table>";
        $html .= "</body></html>";

        return $html;
    }
}
