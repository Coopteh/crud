<?php

namespace Crud\Views;

class RecordView
{
    public function render(array $records, string $action = 'list'): void
    {
        // Явно задаём отображаемые колонки
        $displayColumns = ['id_product', 'name','description','prise', 'is_deleted','updated', 'option'];
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Таблица productsts</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <h2 class="mb-4">Записи из таблицы <code>productsts</code></h2>

                <!-- Кнопка "Добавить запись" -->
                <a href="?action=insert" class="btn btn-primary mb-3">Добавить запись</a>

                <?php if ($action === 'insert' || $action === 'edit'): ?>
                    <!-- Форма добавления/редактирования -->
                    <div class="card mb-4 p-3 shadow-sm">
                        <h5><?= $action === 'edit' ? 'Редактировать запись' : 'Новая запись' ?></h5>
                        <form method="POST" action="?action=<?= $action === 'edit' ? 'update' : 'store' ?>">
                            <?php if ($action === 'edit' && !empty($records['edit_data'])): ?>
                                <input type="hidden" name="id_product" value="<?= htmlspecialchars($records['edit_data']['id_product']) ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label for="name" class="form-label">Название</label>
                                <input type="text"
                                       class="form-control"
                                       id_product="name"
                                       name="name"
                                       value="<?= htmlspecialchars($records['edit_data']['name'] ?? '') ?>"
                                       maxlength="20"
                                       required>
                            </div>
                            <button type="submit" class="btn btn-success"><?= $action === 'edit' ? 'Сохранить' : 'Добавить' ?></button>
                            <a href="?action=list" class="btn btn-secondary">Отмена</a>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Таблица записей -->
                <div class="table-responsive bg-white shadow-sm rounded p-3 mb-4">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <?php foreach ($displayColumns as $col): ?>
                                    <th><?= htmlspecialchars($col === 'is_deleted' ? 'Помечено на удаление' : $col) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($records['data']) && is_array($records['data'])): ?>
                                <?php foreach ($records['data'] as $row): ?>
                                    <tr class="<?= !empty($row['is_deleted']) ? 'table-danger' : '' ?>">
                                        <td><?= htmlspecialchars($row['id_product']) ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['description']) ?></td>
                                        <td><?= htmlspecialchars($row['prise']) ?></td>

                                        <td>
                                            <?= !empty($row['is_deleted']) ? '✅ Да' : '❌ Нет' ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['updated']) ?></td>
                                        <td>
                                            <!-- Кнопка Редактировать -->
                                            <a href="?action=edit&id_product=<?= $row['id_product'] ?>" class="btn btn-sm btn-warning">Редактировать</a>
                                            <!-- Кнопка пометить/снять пометку на удаление -->
                                            <a href="?action=toggle_delete&id_product=<?= $row['id_product'] ?>"
                                               class="btn btn-sm <?= !empty($row['is_deleted']) ? 'btn-outline-danger' : 'btn-danger' ?>"
                                               onclick="return confirm('<?= !empty($row['is_deleted']) ? 'Снять пометку на удаление?' : 'Пометить на удаление?' ?>')">
                                                <?= !empty($row['is_deleted']) ? 'Снять пометку' : 'Удалить' ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= count($displayColumns) ?>" class="text-center">Записей нет</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Блок пагинации -->
                <?php if (!empty($records['total_pages']) && $records['total_pages'] > 1): ?>
                    <nav aria-label="Навигация по страницам">
                        <ul class="pagination justify-content-center">
                            <!-- Ссылка "Назад" -->
                            <?php if ($records['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $records['current_page'] - 1 ?>&action=list" tabindex="-1">Назад</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">Назад</span></li>
                            <?php endif; ?>

                            <!-- Номера страниц -->
                            <?php for ($i = 1; $i <= $records['total_pages']; $i++): ?>
                                <li class="page-item <?= $i == $records['current_page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&action=list"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Ссылка "Вперед" -->
                            <?php if ($records['current_page'] < $records['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $records['current_page'] + 1 ?>&action=list">Вперед</a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">Вперед</span></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </body>
        </html>
        <?php
    }
}