## CRUD операции с таблицей products

Продолжаем работу crud-проектом  
Работа с git (предварительная работа)  
```
запустите Git Bash 
перейдите в каталог c:/xampp/htdocs/crud
> cd c:/xampp/htdocs/crud

Запуште изменения с предыдущего занятия, если вчера этого не сделали
Получите обновления с сервера
git pull
cоздайте новую ветку:
git checkout -b task-13-04-26-products

Запустите XAMP + Apache + mySQL
```
### Задание на работу с базой данных  
Создайте новую пустую базу данных is231 в MySQL  
Для этого нужно воспользоваться phpMyAdmin (localhost/phpmyadmin/)  
В новой базе создайте таблицу products (продукция) со следующими полями, типами данных и ограничениями:  
```
id_product — int, auto_increment, primary_key
name — varchar(120), not null, unique
description — text
image — varchar(120)
price — float, not null
created — datetime, по-умолчанию CURRENT_TIMESTAMP
updated — datetime, по-умолчанию CURRENT_TIMESTAMP
```

Основное задание  
измените CRUD-проект для своего аккаунта, адаптировав все операции под таблицу products   
```
1. Добавьте в таблицу product 2-3 записи, чтобы было с чем работать
2. Начните с соединения с базой данных
$dsn = 'mysql:dbname=is231;host=127.0.0.1';
в конструкторе модели Record
3. Измените таблицу в представлении RecordView чтобы отображались новые поля таблицы
4. Измените форму ввода новой записи и редактирования существующей
5. Добейтесь, чтобы все CRUD-операции работали
```

Закоммитьте и запуште изменения
```
> git status
> git add .
> git status
> git commit -m "Crud для product"
> git push
```
Сдайте работу - создав запрос на изменения Pull Request  
- зайдите на github и создайте Pull Request со своего аккаунта в исходный репозиторий (для аккаунта Coopteh)

