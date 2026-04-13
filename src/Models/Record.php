<?php
namespace Crud\Models;
use PDO;
use PDOException;

class Record
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = 'mysql:dbname=is231;host=127.0.0.1';
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
        $sql = "SELECT id_product, name, description, image, price, created, updated 
                FROM products ORDER BY id_product DESC";
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
        return (int)$this->pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id_product, name, description, image, price, created, updated 
                                     FROM products WHERE id_product = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function insert(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO products (name, description, image, price) 
                                     VALUES (:name, :description, :image, :price)");
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE products SET name = :name, description = :description, 
                                     image = :image, price = :price, updated = NOW() 
                                     WHERE id_product = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id_product = :id");
        return $stmt->execute(['id' => $id]);
    }
}