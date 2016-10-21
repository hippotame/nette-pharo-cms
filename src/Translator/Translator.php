<?php

 /*
  * Pharo
  */

 namespace Pharo;

 
 use Nette;
 
 /**
  * Description of Translator
  *
  * @author hippo
  */
 class Translator implements Nette\Localization\ITranslator {

     public function translate($message, $count = NULL) {
         return $message;
     }

 }
 