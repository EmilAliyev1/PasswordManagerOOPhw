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

        $iv = "";

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

    public function getUserById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function changePassword(
        int $userId,
        string $oldPassword,
        string $newPassword
    ): bool
    {
        $user = $this->getUserById($userId);

        if (!$user)
        {
            return false;
        }

        if (!password_verify(
            $oldPassword,
            $user["password_hash"]
        ))
        {
            return false;
        }

        $userKey =
            Encryption::decryptUserKey(
                base64_decode(
                    $user["encrypted_user_key"]
                ),
                $oldPassword,
                base64_decode(
                    $user["key_iv"]
                )
            );

        $newIv = openssl_random_pseudo_bytes(16);

        $newEncryptedKey =
            Encryption::encryptUserKey(
                $userKey,
                $newPassword,
                $newIv
            );

        $newHash =
            password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );

        $stmt = $this->db->prepare("
            UPDATE users
            SET
                password_hash = ?,
                encrypted_user_key = ?,
                key_iv = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $newHash,
            base64_encode($newEncryptedKey),
            base64_encode($newIv),
            $userId
        ]);
    }
}