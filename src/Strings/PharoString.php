<?php

 /*
  * To change this license header, choose License Headers in Project Properties.
  * To change this template file, choose Tools | Templates
  * and open the template in the editor.
  */

 namespace Nette\Utils;

 /**
  * Description of PharoString
  *
  * @author hippo
  */
 class PharoString extends Strings {
     //put your code here

     /**
      * camelCaseAction name -> dash-separated.
      * @param  string
      * @return string
      */
     public static function action2path($s) {
         $s = preg_replace('#(.)(?=[A-Z])#', '$1-', $s);
         $s = strtolower($s);
         $s = rawurlencode($s);
         return $s;
     }

     /**
      * dash-separated -> camelCaseAction name.
      * @param  string
      * @return string
      */
     public static function path2action($s) {
         $s = preg_replace('#-(?=[a-z])#', ' ', $s);
         $s = lcfirst(ucwords($s));
         $s = str_replace(' ', '', $s);
         return $s;
     }

     /**
      * PascalCase:Presenter name -> dash-and-dot-separated.
      * @param  string
      * @return string
      */
     public static function presenter2path($s) {
         $s = strtr($s, ':', '.');
         $s = preg_replace('#([^.])(?=[A-Z])#', '$1-', $s);
         $s = strtolower($s);
         $s = rawurlencode($s);
         return $s;
     }

     /**
      * dash-and-dot-separated -> PascalCase:Presenter name.
      * @param  string
      * @return string
      */
     public static function path2presenter($s) {
         $s = preg_replace('#([.-])(?=[a-z])#', '$1 ', $s);
         $s = ucwords($s);
         $s = str_replace('. ', ':', $s);
         $s = str_replace('- ', '', $s);
         return $s;
     }

     /**
      * Url encode.
      * @param  string
      * @return string
      */
     public static function param2path($s) {
         return str_replace('%2F', '/', rawurlencode($s));
     }

 }
 