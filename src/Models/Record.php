<?php

class Record
{
    public int $id = 0;
    public string $name = '';

    public static function all(): array
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM records");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Record::class);
    }

    public static function find(int $id): ?Record
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM records WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Record::class);
        return $stmt->fetch() ?: null;
    }

    public function save(): bool
    {
        global $pdo;

        if ($this->id === 0) {
            $stmt = $pdo->prepare("INSERT INTO records (name) VALUES (?)");
            $stmt->execute([$this->name]);
            $this->id = (int)$pdo->lastInsertId();
            return true;
        }

        $stmt = $pdo->prepare("UPDATE records SET name = ? WHERE id = ?");
        return $stmt->execute([$this->name, $this->id]);
    }

    public function delete(): bool
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM records WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}
