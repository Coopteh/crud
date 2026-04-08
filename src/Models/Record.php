<?php
namespace Crud\Models;

use PDO;

class Record
{
    private PDO $pdo;
    
    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=127.0.0.1:3307;dbname=example1;charset=utf8mb4',
            'root', '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    
    public function getAll(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare('SELECT id, name, deleted FROM table1 ORDER BY id LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCount(): int
    {
        return (int)$this->pdo->query('SELECT COUNT(*) FROM table1')->fetchColumn();
    }
    
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name FROM table1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
    
    public function create(string $name): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO table1 (name, deleted) VALUES (:name, 0)');
        $stmt->execute(['name' => $name]);
    }
    
    public function update(int $id, string $name): void
    {
        $stmt = $this->pdo->prepare('UPDATE table1 SET name = :name WHERE id = :id');
        $stmt->execute(['id' => $id, 'name' => $name]);
    }
    
    public function toggleDelete(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE table1 SET deleted = IF(deleted = 0, 1, 0) WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}