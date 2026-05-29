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

        require_once "Encryption.php";

        $userKey = Encryption::decryptUserKey(
            base64_decode($user["encrypted_user_key"]),
            $password,
            base64_decode($user["key_iv"])
        );

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["user_key"] = base64_encode($userKey);

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