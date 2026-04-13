<?php
namespace Crud\Models;

use PDO;

class Record
{
    private PDO $pdo;
    
    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=127.0.0.1;dbname=is231;charset=utf8mb4',
            'root', '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    
    public function getAll(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare('SELECT id_product, name, description, price, created, updated FROM products ORDER BY id_product LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCount(): int
    {
        return (int)$this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    }
    
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_product, name, description, price FROM products WHERE id_product = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
    
    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO products (name, description, price) VALUES (:name, :description, :price)');
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'price' => $data['price']
        ]);
    }
    
    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare('UPDATE products SET name = :name, description = :description, price = :price WHERE id_product = :id');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'price' => $data['price']
        ]);
    }
    
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id_product = :id');
        $stmt->execute(['id' => $id]);
    }
}