<?php

namespace App\Services;

class EncryptionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    protected string $cipher = 'aes-256-gcm';

    /**
     * Encrypt a payload for a specific client using AES-GCM + RSA-OAEP
     */
    public function encryptDataForClient(array $data, string $clientPublicKey): array
    {
        // 1️⃣ Generate AES key and IV
        $aesKey = random_bytes(32); // AES-256 key
        $iv = random_bytes(12);     // 12 bytes recommended for GCM
        $tag = '';

        // 2️⃣ Encrypt data with AES-GCM
        $plaintext = json_encode($data);

        $ciphertext = openssl_encrypt(
            $plaintext,
            $this->cipher,
            $aesKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($ciphertext === false) {
            throw new \RuntimeException('AES encryption failed');
        }

        // 3️⃣ Load and validate the client public key
        $pubKey = openssl_pkey_get_public($clientPublicKey);
        if ($pubKey === false) {
            throw new \RuntimeException('Invalid client public key');
        }

        // 4️⃣ Encrypt AES key using RSA-OAEP
        if (!openssl_public_encrypt($aesKey, $encryptedKey, $pubKey, OPENSSL_PKCS1_OAEP_PADDING)) {
            throw new \RuntimeException('RSA public key encryption failed');
        }

        // 5️⃣ Return Base64-encoded encrypted data
        return [
            'ne' => base64_encode($encryptedKey),
            'iv' => base64_encode($iv),
            'g' => base64_encode($tag),
            'ta' => base64_encode($ciphertext),
        ];
    }
}
