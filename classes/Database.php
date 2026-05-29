<?php

class Database
{
    private string $host = "localhost";
    private string $dbname = "password_manager";
    private string $username = "passworduser";
    private string $password = "StrongPassword123";

    private ?PDO $connection = null;

    public function connect(): PDO
    {
        if ($this->connection === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";

            $this->connection = new PDO(
                $dsn,
                $this->username,
                $this->password
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }

        return $this->connection;
    }
}