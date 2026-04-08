<?php

namespace Crud\Models;

class Record
{
    private \PDO $pdo;

    public function __construct()
    {
        $dsn = 'mysql:dbname=example1;host=127.0.0.1';
        $user = 'root';
        $password = '';
        $this->pdo = new \PDO($dsn, $user, $password);
    }

    public function loadData()
    {
        $sql = "SELECT * FROM table1";
        $smtp = $this->pdo->query($sql);
        return $smtp->fetchAll();
    }

    public function delete(string $id): void
    {
        $sql = "UPDATE table1 SET is_deleted = 1 - is_deleted WHERE id = ?";
        $smtp = $this->pdo->prepare($sql);
        $smtp->execute([$id]);
    }

    public function loadRecord(string $id): ?array
    {
        $sql = "SELECT * FROM table1 WHERE id = ?";
        $smtp = $this->pdo->prepare($sql);
        $smtp->execute([$id]);
        $row = $smtp->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }

    public function saveRecord(string $id=null): void
    {
        if (isset($id)) {
            $sql = "UPDATE table1 SET `name` = ? WHERE `id` = ?";
            $smtp = $this->pdo->prepare($sql);
            $smtp->execute([$_POST['name'], $_POST['id']]);
        } else {
            $sql = "INSERT INTO table1 (`name`) VALUES ( ? )";
            $smtp = $this->pdo->prepare($sql);
            $smtp->execute([$_POST['name']]);      
        }
    }
}
