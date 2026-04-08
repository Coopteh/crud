<?php

class RecordView
{
    public function list(array $records, int $currentPage = 1, int $totalPages = 1): string
    {
        $html = '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .card-table { box-shadow: 0 2px 12px rgba(0,0,0,0.08); border: none; border-radius: 12px; }
        .table th { font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-action { transition: transform 0.15s; }
        .btn-action:hover { transform: translateY(-1px); }
        .empty-state { color: #6c757d; font-style: italic; }
        .page-link { border-radius: 8px !important; margin: 0 2px; }
        .header-title { font-weight: 700; color: #212529; }
        
        /* Стиль для "удалённой" строки */
        .row-deleted {
            position: relative;
            opacity: 0.6;
            text-decoration: line-through;
            background: #fff0f0 !important;
        }
        .row-deleted::after {
            content: "✕";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            color: #dc3545;
            font-weight: bold;
            pointer-events: none;
        }
        .row-deleted td {
            pointer-events: none;
        }
        .row-deleted .btn-delete {
            pointer-events: auto;
            opacity: 1;
        }
    </style>
</head>
<body class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="header-title mb-0"><i class="bi bi-list-ul me-2"></i>Записи</h1>
        <a href="?action=insert" class="btn btn-primary btn-action">
            <i class="bi bi-plus-lg me-1"></i>Добавить
        </a>
    </div>
    
    <div class="card card-table">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Название</th>
                        <th style="width: 200px;" class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>';

        if (empty($records)) {
            $html .= '<tr><td colspan="3" class="text-center py-5 empty-state"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Нет записей</td></tr>';
        } else {
            foreach ($records as $r) {
                $html .= '<tr id="row-' . $r->id . '">';
                $html .= '<td class="align-middle"><span class="badge bg-secondary">' . $r->id . '</span></td>';
                $html .= '<td class="align-middle fw-medium record-name">' . htmlspecialchars($r->name) . '</td>';
                $html .= '<td class="text-end align-middle">';
                $html .= '<a href="?action=edit&id=' . $r->id . '" class="btn btn-sm btn-outline-warning btn-action me-1" title="Редактировать"><i class="bi bi-pencil"></i></a>';
                // Кнопка удаления с data-атрибутом для JS
                $html .= '<button type="button" class="btn btn-sm btn-outline-danger btn-action btn-delete" 
                        data-id="' . $r->id . '" 
                        data-name="' . htmlspecialchars($r->name, ENT_QUOTES) . '"
                        title="Пометить к удалению (визуально)">
                        <i class="bi bi-trash"></i></button>';
                $html .= '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</tbody>
            </table>
        </div>
    </div>';

        $html .= $this->pagination($currentPage, $totalPages);
        
        // Скрипт для визуального "удаления"
        $html .= '<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-delete").forEach(btn => {
                btn.addEventListener("click", function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const row = document.getElementById("row-" + id);
                    
                    if (row && !row.classList.contains("row-deleted")) {
                        if (confirm("Визуально пометить \"" + name + "\" как удалённую?\n(Запись не будет удалена из базы)")) {
                            row.classList.add("row-deleted");
                            this.innerHTML = "<i class=\"bi bi-arrow-counterclockwise\"></i>";
                            this.title = "Вернуть запись";
                            this.classList.replace("btn-outline-danger", "btn-outline-success");
                            
                            // Опционально: сохранить состояние в localStorage (необязательно)
                            // localStorage.setItem("deleted_" + id, "1");
                        }
                    } else if (row && row.classList.contains("row-deleted")) {
                        // Вернуть запись
                        row.classList.remove("row-deleted");
                        this.innerHTML = "<i class=\"bi bi-trash\"></i>";
                        this.title = "Пометить к удалению (визуально)";
                        this.classList.replace("btn-outline-success", "btn-outline-danger");
                        // localStorage.removeItem("deleted_" + id);
                    }
                });
            });
            
            // Опционально: восстановить визуальные пометки после перезагрузки
            /*
            document.querySelectorAll("[id^=\"row-\"]").forEach(row => {
                const id = row.id.replace("row-", "");
                if (localStorage.getItem("deleted_" + id) === "1") {
                    row.classList.add("row-deleted");
                    const btn = row.querySelector(".btn-delete");
                    if (btn) {
                        btn.innerHTML = "<i class=\"bi bi-arrow-counterclockwise\"></i>";
                        btn.title = "Вернуть запись";
                        btn.classList.replace("btn-outline-danger", "btn-outline-success");
                    }
                }
            });
            */
        });
        </script>';
        
        $html .= '</body></html>';

        return $html;
    }

    private function pagination(int $currentPage, int $totalPages): string
    {
        if ($totalPages <= 1) return '';

        $html = '<nav class="my-4"><ul class="pagination justify-content-center flex-wrap gap-1">';

        $html .= $currentPage > 1 
            ? '<li class="page-item"><a class="page-link" href="?action=index&page=1" title="В начало"><i class="bi bi-chevron-double-left"></i></a></li>'
            : '<li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-double-left"></i></a></li>';

        $html .= $currentPage > 1
            ? '<li class="page-item"><a class="page-link" href="?action=index&page=' . ($currentPage - 1) . '"><i class="bi bi-chevron-left"></i></a></li>'
            : '<li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>';

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="?action=index&page=' . $i . '">' . $i . '</a></li>';
            }
        }

        $html .= $currentPage < $totalPages
            ? '<li class="page-item"><a class="page-link" href="?action=index&page=' . ($currentPage + 1) . '"><i class="bi bi-chevron-right"></i></a></li>'
            : '<li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>';

        $html .= $currentPage < $totalPages
            ? '<li class="page-item"><a class="page-link" href="?action=index&page=' . $totalPages . '" title="В конец"><i class="bi bi-chevron-double-right"></i></a></li>'
            : '<li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-double-right"></i></a></li>';

        $html .= '</ul></nav>';
        return $html;
    }

    public function form(?Record $record = null): string
    {
        $id = $record ? $record->id : '';
        $name = $record ? htmlspecialchars($record->name) : '';
        $btn = $id ? 'Обновить' : 'Создать';
        $title = $id ? 'Редактирование' : 'Создание';
        $action = $id ? "?action=update&id=$id" : "?action=create";
        $icon = $id ? 'bi-pencil-square' : 'bi-plus-circle';

        return "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$title</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'>
    <style>
        body { background: #f8f9fa; }
        .form-card { box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: none; border-radius: 16px; }
        .form-title { font-weight: 700; color: #212529; }
    </style>
</head>
<body class='container py-5'>
    <div class='row justify-content-center'>
        <div class='col-md-6 col-lg-5'>
            <div class='card form-card'>
                <div class='card-body p-4 p-md-5'>
                    <h2 class='form-title mb-4 text-center'><i class='bi $icon me-2'></i>$title</h2>
                    <a href='?action=index' class='btn btn-outline-secondary mb-4'><i class='bi bi-arrow-left me-1'></i>Назад</a>
                    <form method='post' action='$action'>
                        <div class='mb-4'>
                            <label class='form-label fw-semibold'>Название <span class='text-danger'>*</span></label>
                            <input type='text' name='name' value='$name' class='form-control form-control-lg' required placeholder='Введите название'>
                        </div>
                        <button type='submit' class='btn btn-primary w-100 btn-lg'>$btn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
    }

    private function error(string $message): string
    {
        return "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Ошибка</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css'>
    <style>
        body { background: #f8f9fa; }
        .error-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(220,53,69,0.15); }
    </style>
</head>
<body class='container py-5'>
    <div class='row justify-content-center'>
        <div class='col-md-6 col-lg-5'>
            <div class='card error-card'>
                <div class='card-body text-center p-4 p-md-5'>
                    <div class='text-danger mb-3'>
                        <i class='bi bi-exclamation-triangle-fill fs-1'></i>
                    </div>
                    <h5 class='fw-bold mb-3'>Произошла ошибка</h5>
                    <p class='text-muted mb-4'>$message</p>
                    <a href='?action=index' class='btn btn-secondary'><i class='bi bi-house me-1'></i>На главную</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
    }
}