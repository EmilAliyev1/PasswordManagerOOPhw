<?php

require_once "Database.php";
require_once "Encryption.php";

class User
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function register(
        string $username,
        string $password
    ): bool
    {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $userKey = Encryption::generateUserKey();

        $iv = openssl_random_pseudo_bytes(16);

        $encryptedKey = Encryption::encryptUserKey(
            $userKey,
            $password,
            $iv
        );

        $stmt = $this->db->prepare("
            INSERT INTO users
            (
                username,
                password_hash,
                encrypted_user_key,
                key_iv
            )
            VALUES
            (
                ?, ?, ?, ?
            )
        ");

        return $stmt->execute([
            $username,
            $passwordHash,
            base64_encode($encryptedKey),
            base64_encode($iv)
        ]);
    }
}