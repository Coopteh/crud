<?php

namespace App\Views;

class RecordView
{
    public function renderList(array $records): void
    {
        ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записи из table1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Данные таблицы <code>products</code></h1>

        <!-- Кнопка "Добавить запись" -->
        <a href="?action=insert" class="btn btn-primary mb-3">Добавить запись</a>

        <?php if (empty($records)): ?>
            <div class="alert alert-warning" role="alert">
                В таблице нет записей.
            </div>
        <?php else: ?>
            <div class="table-responsive shadow-sm bg-white rounded p-3">
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <?php
                            $headers = array_keys($records[0]);
                            foreach ($headers as $header): ?>
                                <th><?= htmlspecialchars($header, ENT_QUOTES, 'UTF-8') ?></th>
                            <?php endforeach; ?>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $row): ?>
                            <tr>
                                <?php foreach ($row as $value): ?>
                                    <td><?= htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <?php endforeach; ?>
                                <?php $id = $row['id_product'] ?? null;
                                if ($id !== null): ?>
                                <td>                                     
                                    <a href="?action=edit&id=<?= $id ?>" class="btn btn-sm btn-outline-primary">Редактировать</a>
                                    <a href="?action=delete&id=<?= $id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить запись?')">Удалить</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <a href="?action=index" class="btn btn-secondary mt-3">← Назад к списку</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    }

    /**
     * Рендеринг формы добавления/редактирования
     */
    public function renderForm(?array $record, array $fields, ?int $id = null): void
    {
        $isEdit = $id !== null;
        $title = $isEdit ? 'Редактирование записи' : 'Добавление записи';
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
                    
                    <?php foreach ($fields as $field): ?>
                        <div class="mb-3">
                            <label for="<?= $field['name'] ?>" class="form-label">
                                <?= htmlspecialchars($field['name']) ?>
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="<?= $field['name'] ?>" 
                                name="<?= $field['name'] ?>"
                                value="<?= htmlspecialchars($record[$field['name']] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                <?= $field['null'] === 'NO' ? 'required' : '' ?>
                            >
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
}
