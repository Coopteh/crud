<?php
namespace Crud\Models;

use PDO;
use PDOException;

class Record
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=example1;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, 'root', '', $options);
        } catch (PDOException $e) {
            die("Ошибка БД: " . $e->getMessage());
        }
    }

    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        $sql = "SELECT id, name, is_deleted FROM table1 ORDER BY id";
        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        return $this->pdo->query($sql)->fetchAll();
    }

    public function countAll(): int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM table1")->fetchColumn();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id, name FROM table1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function insert(string $name): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO table1 (name, is_deleted) VALUES (:name, 0)");
        return $stmt->execute(['name' => $name]);
    }

    public function update(int $id, string $name): bool
    {
        $stmt = $this->pdo->prepare("UPDATE table1 SET name = :name WHERE id = :id");
        return $stmt->execute(['name' => $name, 'id' => $id]);
    }

    /**
     * Переключает статус удаления: 0 -> 1, 1 -> 0
     */
    public function toggleStatus(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE table1 SET is_deleted = IF(is_deleted = 1, 0, 1) WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}