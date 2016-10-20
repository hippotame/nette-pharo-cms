<?php

 namespace Pharo;

define('CRYPT_MODE_HEX', 1);
 define('CRYPT_MODE_BASE64', 2);
 define('CRYPT_MODE_BIN', 3);

 class XorCrypt {

     const _CRYPT_KEY = '#_P|-|@R0_#';

     static $_crypt_mode = CRYPT_MODE_HEX;

     /**
      * 
      * Zakoduje string
      * @param string $value
      * @return string
      */
     public static function encrypt($value) {
         $value = (string) $value;
         $_crypt_key = (string) self::_CRYPT_KEY;
         $encrypt = '';
         for ($i = 0; $i < strlen($value); $i ++) {
             $encrypt .= $value[$i] ^ $_crypt_key[$i % strlen($_crypt_key)];
         }
         if (self::$_crypt_mode == CRYPT_MODE_BIN) {
             return @$encrypt;
         }
         if (self::$_crypt_mode == CRYPT_MODE_BASE64) {
             return base64_encode(@$encrypt);
         }
         if (self::$_crypt_mode == CRYPT_MODE_HEX) {
             return self::encodeHex(base64_encode(@$encrypt));
         }
     }

     /**
      * 
      * @param string $value
      * @return string
      */
     public static function decrypt($value) {
         if (self::$_crypt_mode == CRYPT_MODE_HEX) {
             $value = base64_decode(self::decodeHex($value));
         }
         if (self::$_crypt_mode == CRYPT_MODE_BASE64) {
             $value = (string) base64_decode($value);
         }
         $_crypt_key = (string) self::_CRYPT_KEY;
         $decrypt = '';
         for ($i = 0; $i < strlen($value); $i ++) {
             $decrypt .= $value[$i] ^ $_crypt_key[$i % strlen($_crypt_key)];
         }
         return $decrypt;
     }

     private static function encodeHex($value) {
         $value = (string) $value;
         $encrypt = '';
         for ($i = 0; $i < strlen($value); $i ++) {
             $encrypt .= dechex(ord($value[$i]));
         }
         return $encrypt;
     }

     private static function decodeHex($value) {
         $value = (string) $value;
         $decrypt = '';
         for ($i = 0; $i < strlen($value); $i += 2) {
             $decrypt .= chr(hexdec(substr($value, $i, 2)));
         }
         return $decrypt;
     }

 }
 