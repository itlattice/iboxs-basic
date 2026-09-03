<?php
namespace iboxs\basic\lib\encryption;

class Encryption{
    protected string $string;

    public function __construct(string $string = '')
    {
        $this->string = $string;
    }

    /**
     * AES加密
     * @param string $key 加密密钥
     * @param string $iv 加密初始向量，长度必须与加密方法要求的长度相同
     * @param string $method 加密方法，默认为AES-256-CBC
     * @param int $options 加密选项，默认为0
     * @return string 加密后的字符串或解密后的字符串
     */
    public function AES($key, $iv = '', $method = 'AES-256-CBC', $options = 0): string
    {
        return openssl_encrypt($this->string, $method, $key, $options, $iv);
    }

    /**
     * AES解密
     * @param string $key 解密密钥
     * @param string $iv 解密初始向量，长度必须与加密方法要求的长度相同
     * @param string $method 解密方法，默认为AES-256-CBC
     * @param int $options 解密选项，默认为0
     * @return string 解密后的字符串
     */
    public function AESDecrypt($key, $iv = '', $method = 'AES-256-CBC', $options = 0): string
    {
        return openssl_decrypt($this->string, $method, $key, $options, $iv);
    }
    
    /**
     * RSA公钥加密
     * @param string $publicKey 公钥
     */
    public function RSAWithPublicKey($publicKey, $padding = OPENSSL_PKCS1_PADDING): string
    {
        openssl_public_encrypt($this->string, $encrypted, $publicKey, $padding);
        return base64_encode($encrypted);
    }

    /**
     * RSA私钥解密
     * @param string $privateKey 私钥
     * @param int $padding 填充方式，默认为OPENSSL_PKCS1_PADDING
     * @return string 解密后的字符串
     */
    public function RSAPrivateKeyDecrypt($privateKey, $padding = OPENSSL_PKCS1_PADDING): string
    {
        openssl_private_decrypt(base64_decode($this->string), $decrypted, $privateKey, $padding);
        return $decrypted;
    }

    /**
     * RSA私钥加密
     * @param string $privateKey 私钥
     * @param int $padding 填充方式，默认为OPENSSL_PKCS1_PADDING
     * @return string 加密后的字符串
     */
    public function RSAWithPrivateKey($privateKey, $padding = OPENSSL_PKCS1_PADDING): string
    {
        openssl_private_encrypt($this->string, $encrypted, $privateKey, $padding);
        return base64_encode($encrypted);
    }

    /**
     * RSA公钥解密
     * @param string $publicKey 公钥
     * @param int $padding 填充方式，默认为OPENSSL_PKCS1_PADDING
     * @return string 解密后的字符串
     */
    public function RSAPublicKeyDecrypt($publicKey, $padding = OPENSSL_PKCS1_PADDING): string
    {
        openssl_public_decrypt(base64_decode($this->string), $decrypted, $publicKey, $padding);
        return $decrypted;
    }

    /**
     * SHA256/SHA512哈希
     * @param string $algorithm 哈希算法，默认为SHA256
     * @return string 哈希后的字符串
     */
    public function hash($algorithm = 'SHA256'): string
    {
        return hash($algorithm, $this->string);
    }

    /**
     * HMAC哈希
     * @param string $key 哈希密钥
     * @param string $algorithm 哈希算法，默认为SHA256
     * @return string HMAC哈希后的字符串
     */
    public function hmac($key, $algorithm = 'SHA256'): string
    {
        return hash_hmac($algorithm, $this->string, $key);
    }

    /**
     * ECC加密
     * @param string $publicKey 公钥
     * @return string 加密后的字符串
     */
    public function ECCWithPublicKey($publicKey): string
    {
        $publicKeyResource = openssl_pkey_get_public($publicKey);
        $encrypted = '';
        openssl_public_encrypt($this->string, $encrypted, $publicKeyResource, OPENSSL_PKCS1_OAEP_PADDING);
        return base64_encode($encrypted);
    }

    /**
     * ECC解密
     * @param string $privateKey 私钥
     * @return string 解密后的字符串
     */
    public function ECCWithPrivateKey($privateKey): string
    {
        $encryptedData = base64_decode($this->string);
        $privateKeyResource = openssl_pkey_get_private($privateKey);
        $decrypted = '';
        openssl_private_decrypt($encryptedData, $decrypted, $privateKeyResource, OPENSSL_PKCS1_OAEP_PADDING);
        return $decrypted;
    }
}
