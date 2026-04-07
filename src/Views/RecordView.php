<?php
namespace Crud\Views;
class RecordView {
    public function render(array $records): void{
        // Получаем заголовки из ключей первой записи и убираем возможные дубли
        $headers = [];
        if (!empty($records)) {
            $headers = array_unique(array_keys($records[0]));
        }
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Таблица table1</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <h2 class="mb-4">Записи из таблицы <code>table1</code></h2>

                <div class="table-responsive bg-white shadow-sm rounded p-3">
                    <table class="table table-hover table-striped align-middle">
                        <?php if (!empty($headers)): ?>
                            <thead class="table-dark">
                                <tr>
                                    <?php foreach ($headers as $header): ?>
                                        <th><?= htmlspecialchars($header) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                        <?php endif; ?>
                        <tbody>
                            <?php foreach ($records as $row): ?>
                                <tr>
                                    <?php foreach ($row as $value): ?>
                                        <td><?= htmlspecialchars((string)$value) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}