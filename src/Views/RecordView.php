<?php

namespace App\Views;

class RecordView
{
    public function renderList(array $records, ?string $message = null, string $type = 'info'): void
    {
        ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>События</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Таблица <code>Events</code></h1>

        <!-- Flash-сообщение -->
        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <a href="?action=insert" class="btn btn-primary mb-3">+ Добавить запись</a>

        <?php if (empty($records)): ?>
            <div class="alert alert-warning">В таблице нет записей.</div>
        <?php else: ?>
            <div class="table-responsive shadow-sm bg-white rounded p-3">
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <?php
                            $headers = array_keys($records[0]);
                            // Скрываем служебную колонку is_deleted
                            $headers = array_filter($headers, fn($h) => $h !== 'is_deleted');
                            foreach ($headers as $header): ?>
                                <th><?= htmlspecialchars($header) ?></th>
                            <?php endforeach; ?>
                            <th style="width: 220px;">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $row): ?>
                            <tr>
                                <?php foreach ($headers as $header): ?>
                                    <td><?= htmlspecialchars($row[$header] ?? '') ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <a href="?action=edit&id=<?= (int)$row['id'] ?>"
                                       class="btn btn-sm btn-outline-primary">Редактировать</a>
                                    <a href="?action=delete&id=<?= (int)$row['id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Удалить запись?')">Удалить</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
        <?php
    }

    public function renderForm(?array $record, array $fields, ?int $id = null): void
    {
        $isEdit = $id !== null;
        $title  = $isEdit ? 'Редактирование записи' : 'Добавление записи';
        $action = $isEdit ? 'update' : 'store';
        ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= $title ?></h4>
            </div>
            <div class="card-body">
                <form method="POST" action="?action=<?= $action ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                    <?php endif; ?>

                    <?php foreach ($fields as $field):
                        $name     = $field['name'];
                        $value    = $record[$name] ?? '';
                        $required = $field['null'] === 'NO' && $field['default'] === null;
                        $inputType = $this->resolveInputType($name, $field['type']);
                    ?>
                        <div class="mb-3">
                            <label for="<?= htmlspecialchars($name) ?>" class="form-label">
                                <?= htmlspecialchars($name) ?>
                                <?php if ($required): ?><span class="text-danger">*</span><?php endif; ?>
                            </label>

                            <?php if ($inputType === 'textarea'): ?>
                                <textarea
                                    class="form-control"
                                    id="<?= htmlspecialchars($name) ?>"
                                    name="<?= htmlspecialchars($name) ?>"
                                    rows="4"
                                    <?= $required ? 'required' : '' ?>
                                ><?= htmlspecialchars($value) ?></textarea>
                            <?php else: ?>
                                <input
                                    type="<?= $inputType ?>"
                                    class="form-control"
                                    id="<?= htmlspecialchars($name) ?>"
                                    name="<?= htmlspecialchars($name) ?>"
                                    value="<?= htmlspecialchars($value) ?>"
                                    <?= $required ? 'required' : '' ?>
                                >
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <?= $isEdit ? 'Сохранить изменения' : 'Добавить' ?>
                        </button>
                        <a href="?action=index" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
        <?php
    }

    
    private function resolveInputType(string $name, string $dbType): string
    {
        $name = strtolower($name);

        if ($name === 'description' || str_contains($name, 'text') || str_contains($name, 'content')) {
            return 'textarea';
        }
        if ($name === 'date' || str_contains($name, 'date') || str_contains($dbType, 'date')) {
            return 'date';
        }
        if ($name === 'time' || str_contains($dbType, 'time')) {
            return 'time';
        }
        if (str_contains($dbType, 'int')) {
            return 'number';
        }
        if (str_contains($dbType, 'float') || str_contains($dbType, 'decimal')) {
            return 'number';
        }
        return 'text';
    }
}