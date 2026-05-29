<?php

class Encryption
{
    public static function generateUserKey(): string
    {
        return random_bytes(32);
    }

    public static function encryptUserKey(
        string $userKey,
        string $password,
        string $iv
    ): string
    {
        $iv = random_bytes(16);

        $aesKey = hash(
            'sha256',
            $password,
            true
        );

        return openssl_encrypt(
            $userKey,
            'AES-256-CBC',
            $aesKey,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

    public static function decryptUserKey(
        string $encryptedKey,
        string $password,
        string $iv
    ): string
    {
        $aesKey = hash(
            'sha256',
            $password,
            true
        );

        return openssl_decrypt(
            $encryptedKey,
            'AES-256-CBC',
            $aesKey,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}