<?php 

class MCrypt
{
       
  function __construct()
  {
  }

  public static function encrypt($str) {

      $iv = 'ERCFECX6L3YOBQXU';

      $key = 'dYzxXzu8MYyWyhEDawCumvhegFQquPwj';

      $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

      mcrypt_generic_init($td, $key, $iv);
      $encrypted = mcrypt_generic($td, $str);

      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

      return bin2hex($encrypted);
  }

  public static function decrypt($code) {
      try {
          $code = hex2bin($code);

          $iv = 'ERCFECX6L3YOBQXU';

          $key = 'dYzxXzu8MYyWyhEDawCumvhegFQquPwj';

          $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

          mcrypt_generic_init($td, $key, $iv);
          $decrypted = mdecrypt_generic($td, $code);

          mcrypt_generic_deinit($td);
          mcrypt_module_close($td);

          return trim($decrypted);
      } catch (Exception $e) {
        return null;
      }
      

  }

  public static function  hex2bin($hexdata) {

      $bindata = '';

      for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
      }

      return $bindata;
  }

}