<?php

namespace Crud\Model;

use PDO;
use PDOException;

class Record
{
    private PDO $pdo;

    public function __construct()
    {
        $host = 'localhost';
        $db   = 'example1';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=3307";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Ошибка подключения к БД: " . $e->getMessage());
        }
    }

    public function getAll(int $limit = 0, int $offset = 0): array
    {
        // Базовый SQL-запрос
        $sql = 'SELECT id, name, is_deleted FROM table1 ORDER BY id ASC';
        
        // Если переданы параметры для пагинации ($limit > 0), модифицируем запрос
        if ($limit > 0) {
            $sql .= ' LIMIT :limit OFFSET :offset';
            $stmt = $this->pdo->prepare($sql);
            // Привязываем значения параметров
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        // Если пагинация не нужна, выполняем простой запрос
        return $this->pdo->query($sql)->fetchAll();
    }


    // Получить одну запись по ID
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, is_deleted FROM table1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // Добавить новую запись
    public function insert(string $name): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO table1 (name, is_deleted) VALUES (:name, 0)');
        return $stmt->execute(['name' => $name]);
    }

    // Обновить запись
    public function update(int $id, string $name): bool
    {
        $stmt = $this->pdo->prepare('UPDATE table1 SET name = :name WHERE id = :id');
        return $stmt->execute(['name' => $name, 'id' => $id]);
    }

    // Переключить флаг is_delited (soft delete)
    public function toggleDeleted(int $id): bool
    {
        $stmt = $this->pdo->prepare('UPDATE table1 SET is_deleted = NOT is_deleted WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    // Физическое удаление (если нужно)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM table1 WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function getTotalCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM table1");
        return (int) $stmt->fetchColumn();
    }
}