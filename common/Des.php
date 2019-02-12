<?php
namespace app\common;

class Des {
    public $key = "";

    /* 构造方法 */
    public function encrypt($input) { // 数据加密
        if (empty($input)) {
            return null;
        }
        $size = @mcrypt_get_block_size(MCRYPT_3DES, 'ecb');
        $input = $this->pkcs5_pad($input, $size);
        $key = str_pad($this->key, 24, '0');
        $td = @mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        @mcrypt_generic_deinit($td);
        @mcrypt_module_close($td);
        return $this->strToHex($data);
    }

    public function decrypt($encrypted) { // 数据解密
        if (!$encrypted || empty($encrypted)) {
            return null;
        }
        $encrypted = $this->hexToStr($encrypted);
        if (!$encrypted || empty($encrypted)) {
            return null;
        }
        $key = str_pad($this->key, 24, '0');
        $td = @mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        $decrypted = @mdecrypt_generic($td, $encrypted);
        @mcrypt_generic_deinit($td);
        @mcrypt_module_close($td);
        $y = $this->pkcs5_unpad($decrypted);
        return $y;
    }

    function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text) {
        $pad = ord($text {strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, - 1 * $pad);
    }

    function PaddingPKCS7($data) {
        $block_size = @mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }

    function strToHex($string) {
        $hex = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $iHex = dechex(ord($string[$i]));
            if (strlen($iHex) == 1)
                $hex .= '0' . $iHex;
            else
                $hex .= $iHex;
        }
        $hex = strtoupper($hex);
        return $hex;
    }

    function hexToStr($hex) {
        $string = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

}