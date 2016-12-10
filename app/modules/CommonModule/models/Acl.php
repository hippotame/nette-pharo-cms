<?php

 /*
  * Pharo
  */

 namespace App\Common\model;

 /**
  * Description of Acl
  *
  * @author hippo
  */
 final class Acl {

     const DEF_REGISTERED_RIGHTS = [
         'Common' => ['1', '1', '1'],
         'Front'  => ['1', '1', '1'],
         'Blog'   => ['1', '1', '0'],
         'Forum'  => ['0', '0', '0']
     ];

 }
 