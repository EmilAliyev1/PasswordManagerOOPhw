<?php

require_once "Database.php";
require_once "Encryption.php";

class PasswordVault
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function savePassword(
        int $userId,
        string $siteName,
        string $password,
        string $userKey
    ): bool
    {
        $iv = openssl_random_pseudo_bytes(16);
        
        $encryptedPassword =
            Encryption::encryptPassword(
                $password,
                $userKey,
                $iv
            );

        $stmt = $this->db->prepare("
            INSERT INTO passwords
            (
                user_id,
                site_name,
                encrypted_password,
                password_iv
            )
            VALUES
            (
                ?, ?, ?, ?
            )
        ");

        return $stmt->execute([
            $userId,
            $siteName,
            base64_encode($encryptedPassword),
            base64_encode($iv)
        ]);
    }

    public function getPasswords(
        int $userId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM passwords
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}