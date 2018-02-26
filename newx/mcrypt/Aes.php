<?php
/**
 * @author bean
 * @time 2018/2/26 0026 16:18
 */
namespace newx\mcrypt;

use newx\exception\AppException;

class Aes
{
    /**
     * 编码类型（默认CODE_BASE64）
     */
    const CODE_BASE64 = 'base64';
    const CODE_HEX = 'hex';
    const CODE_BIN = 'bin';

    /**
     * 加密密钥
     * @var string
     */
    protected $key = '4b0e5a4cb41a607756a88bd4f36bddd7';

    /**
     * 加密向量
     * @var string
     */
    protected $iv = '4b0e5a4cb41a607756a88bd4f36bddd7';

    /**
     * 加密模式（默认CBC）
     * @var string
     */
    protected $mode;

    /**
     * 加密类型
     * @var string
     */
    protected $type;

    /**
     * Aes constructor.
     * @param string $mode
     * @throws AppException
     */
    public function __construct($mode = null)
    {
        switch (strlen($this->key)) {
            case 8:
                $this->type = MCRYPT_DES;
                break;
            case 16:
                $this->type = MCRYPT_RIJNDAEL_128;
                break;
            case 32:
                $this->type = MCRYPT_RIJNDAEL_256;
                break;
            default:
                throw new AppException("AES key size must be 8/16/32");
        }

        switch (strtolower($mode)) {
            case MCRYPT_MODE_OFB:
                $this->mode = MCRYPT_MODE_OFB;
                break;
            case MCRYPT_MODE_CFB:
                $this->mode = MCRYPT_MODE_CFB;
                break;
            case MCRYPT_MODE_CBC:
                $this->mode = MCRYPT_MODE_CBC;
                break;
            case MCRYPT_MODE_ECB:
                $this->mode = MCRYPT_MODE_ECB;
                $this->iv = null; // ECB模式不需要加密向量
                break;
            default:
                $this->mode = MCRYPT_MODE_CBC;
        }
    }

    /**
     * 加密
     * @param string $str
     * @param string $code
     * @return string
     */
    public function encrypt($str, $code = null)
    {
        if (!$str) {
            return $str;
        }

        // 1.填充数据
        $str = $this->pkcs5($str);

        // 2.加密
        if ($this->iv) {
            $str = mcrypt_encrypt($this->type, $this->key, $str, $this->mode, $this->iv);
        } else {
            $str = mcrypt_encrypt($this->type, $this->key, $str, $this->mode);
        }

        // 3.编码
        if (!$code) {
            $code = self::CODE_BASE64;
        }
        switch ($code){
            case self::CODE_BASE64:
                $str = base64_encode($str);
                break;
            case self::CODE_HEX:
                $str = bin2hex($str);
                break;
            case self::CODE_BIN:
            default:
                break;
        }
        return $str;
    }

    /**
     * 解密
     * @param $str
     * @param string $code
     * @return bool|string
     */
    public function decrypt($str, $code = null)
    {
        if (!$str) {
            return $str;
        }

        // 1.解码
        if (!$code) {
            $code = self::CODE_BASE64;
        }
        switch ($code){
            case self::CODE_BASE64:
                $str = base64_decode($str);
                break;
            case self::CODE_HEX:
                $str = $this->hex2bin($str);
                break;
            case self::CODE_BIN:
            default:
                break;
        }

        // 2.解密
        if (isset($this->iv)) {
            $str = mcrypt_decrypt($this->type, $this->key, $str, $this->mode, $this->iv);
        } else {
            $str = mcrypt_decrypt($this->type, $this->key, $str, $this->mode);
        }

        // 3.去填充数据
        $str = $this->unpkcs5($str);

        return trim($str);
    }

    /**
     * 初始化加密向量
     * @return $this
     */
    protected function createIv()
    {
        return mcrypt_create_iv(mcrypt_get_iv_size($this->type, $this->mode), MCRYPT_RAND);
    }

    /**
     * PKCS5填充数据
     * @param $str
     * @return string
     */
    protected function pkcs5($str)
    {
        $block_size = mcrypt_get_block_size($this->type, $this->mode);
        $padding_size = $block_size - (strlen($str) % $block_size);
        $padding = str_repeat(chr($padding_size), $padding_size);
        return $str . $padding;
    }

    /**
     * PKCS5去除数据填充
     * @param $str
     * @return bool|string
     */
    protected function unpkcs5($str)
    {
        $padding_size = ord($str{strlen($str) - 1});
        if ($padding_size > strlen($str)) {
            return false;
        }
        if (strspn($str, chr($padding_size), strlen($str) - $padding_size) != $padding_size) {
            return false;
        }
        return substr($str, 0, -1 * $padding_size);
    }

    protected function hex2bin($hex = false)
    {
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
        return $ret;
    }
}