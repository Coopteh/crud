<?php

class Record
{
    public int $id = 0;
    public string $name = '';
    public bool $toDelete = false;

    public static function all(): array
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM records WHERE to_delete = 0");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Record::class);
    }

    public static function paginate(int $page = 1, int $perPage = 10): array
    {
        global $pdo;
        $offset = ($page - 1) * $perPage;
        $stmt = $pdo->prepare("SELECT * FROM records WHERE to_delete = 0 LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Record::class);
    }

    public static function count(): int
    {
        global $pdo;
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM records WHERE to_delete = 0");
        return (int)$stmt->fetch()['cnt'];
    }

    public static function find(int $id): ?Record
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM records WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Record::class);
        return $stmt->fetch() ?: null;
    }

    public function toggleDelete(): bool
    {
        global $pdo;
        $this->toDelete = !$this->toDelete;
        $stmt = $pdo->prepare("UPDATE records SET to_delete = ? WHERE id = ?");
        return $stmt->execute([(int)$this->toDelete, $this->id]);
    }

    public function forceDelete(): bool
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM records WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    public function save(): bool
    {
        global $pdo;

        if ($this->id === 0) {
            $stmt = $pdo->prepare("INSERT INTO records (name, to_delete) VALUES (?, 0)");
            $stmt->execute([$this->name]);
            $this->id = (int)$pdo->lastInsertId();
            return true;
        }

        $stmt = $pdo->prepare("UPDATE records SET name = ? WHERE id = ?");
        return $stmt->execute([$this->name, $this->id]);
    }
}