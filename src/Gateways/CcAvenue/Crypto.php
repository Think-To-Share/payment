<?php

namespace ThinkToShare\Payment\Gateways\CcAvenue;

use ThinkToShare\Payment\Exceptions\DecryptionException;
use ThinkToShare\Payment\Exceptions\EncryptionException;

class Crypto
{
    protected const CIPHER_METHOD = 'AES-256-GCM';
    protected const IV_LENGTH = 12;

    public static function encrypt(string $plainText, string $key): string
    {
        $initVector = openssl_random_pseudo_bytes(self::IV_LENGTH);
        $openMode = openssl_encrypt($plainText, self::CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $initVector, $tag, '', 16);
        $encrypt = bin2hex($initVector).bin2hex( $openMode . $tag);

        if(! $encrypt){
            throw new EncryptionException($plainText);
        }

        return $encrypt;
    }

    public static function decrypt(string $encryptedText,string $key): string
    {
        $encryptedText = hex2bin($encryptedText);
        $tag_length = 16;
        $iv = substr($encryptedText, 0, self::IV_LENGTH);
        $tag = substr($encryptedText, -$tag_length, self::IV_LENGTH);
        $ciphertext = substr($encryptedText, self::IV_LENGTH, -$tag_length);
        $decrypt = openssl_decrypt($ciphertext, self::CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv, $tag, '');

        if(! $decrypt){
            throw new DecryptionException($encryptedText);
        }

        return $decrypt;
    }
}
