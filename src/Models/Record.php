<?php

namespace Crud\Models;

class Record
{
    private $pdo;

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
}
