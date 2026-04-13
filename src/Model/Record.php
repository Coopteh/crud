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
        $db   = 'is231';
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
        $sql = 'SELECT id_product, name, description, prise, is_deleted, updated FROM products ORDER BY id_product ASC';;
        
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


    // Получить одну запись по id_product
    public function getByid_product(int $id_product): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_product, name, is_deleted FROM products WHERE id_product = :id_product');
        $stmt->execute(['id_product' => $id_product]);
        return $stmt->fetch() ?: null;
    }

    // Добавить новую запись
    public function insert(string $name): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO products (name, is_deleted) VALUES (:name, 0)');
        return $stmt->execute(['name' => $name]);
    }

    // Обновить запись
    public function update(int $id_product, string $name): bool
    {
        $stmt = $this->pdo->prepare('UPDATE products SET name = :name WHERE id_product = :id_product');
        return $stmt->execute(['name' => $name, 'id_product' => $id_product]);
    }

    // Переключить флаг is_delited (soft delete)
    public function toggleDeleted(int $id_product): bool
    {
        $stmt = $this->pdo->prepare('UPDATE products SET is_deleted = NOT is_deleted WHERE id_product = :id_product');
        return $stmt->execute(['id_product' => $id_product]);
    }

    // Физическое удаление (если нужно)
    public function delete(int $id_product): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id_product = :id_product');
        return $stmt->execute(['id_product' => $id_product]);
    }

    public function getTotalCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM products");
        return (int) $stmt->fetchColumn();
    }
}