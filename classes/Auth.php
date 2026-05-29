<?php

require_once "Database.php";

class Auth
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function login(
        string $username,
        string $password
    ): bool
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE username = ?
        ");

        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user)
        {
            return false;
        }

        if (!password_verify($password, $user["password_hash"]))
        {
            return false;
        }

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        return true;
    }

    public function logout(): void
    {
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION["user_id"]);
    }
}