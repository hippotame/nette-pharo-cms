<?php

 namespace App\Model\Security;

 /**
  * Description of Password
  *
  * @author hippo
  */
 class Password {

     public static function hash($username, $password) {

         $pass = $username . $password;
         return sha1($pass);
     }

     public static function verify($pass, $pass2) {
         if ($pass == $pass2) {
             return true;
         }
         return false;
     }

 }
 