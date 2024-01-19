<?php

namespace ThinkToShare\Payment\Gateways\SabPaisa;

use ThinkToShare\Payment\Exceptions\DecryptionException;
use ThinkToShare\Payment\Exceptions\EncryptionException;

final class Crypto
{
    private const CIPHER_METHOD = 'aes-128-cbc';
    private const IV_LENGTH = 16;

    private static function normalizeKey(string $key): string
    {
        if (strlen($key) < self::IV_LENGTH) {
            return str_pad($key, self::IV_LENGTH, '0', STR_PAD_RIGHT);
        }

        return substr($key, 0, self::IV_LENGTH);
    }

    public static function encrypt(string $key, string $iv, string $plaintext): string
    {
        $ciphertext = openssl_encrypt($plaintext, self::CIPHER_METHOD, self::normalizeKey($key), OPENSSL_RAW_DATA, $iv);

        if (false === $ciphertext) {
            throw new EncryptionException('Encryption failed.');
        }

        return base64_encode($ciphertext) . ':' . base64_encode($iv);
    }

    public static function decrypt(string $key, string $data, ?string $iv = null): string
    {
        // If an IV hasn't been provided, assume it's encoded with the ciphertext.
        if (is_null($iv)) {
            [$encodedCiphertext, $encodedIv] = explode(':', $data, 2);
            $iv = base64_decode($encodedIv);
            $ciphertext = base64_decode($encodedCiphertext);
        } else {
            $ciphertext = base64_decode($data);
        }

        $plaintext = openssl_decrypt($ciphertext, self::CIPHER_METHOD, self::normalizeKey($key), OPENSSL_RAW_DATA, $iv);

        if (false === $plaintext) {
            throw new DecryptionException('Decryption failed.');
        }

        return $plaintext;
    }
}
