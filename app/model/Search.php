<?php

 /*
  * Pharo
  */

 namespace App\Model;

 /**
  * Description of Search
  *
  * @author hippo
  */
 class Search {

     public $id;
     public $database;

     public function __construct(\Nette\Database\Context $database) {
         $this->database = $database;
     }

     public function SearchUser($s) {
         return $this->database->table('users')
                 ->where('display_name LIKE ', $s)
                 ->where('display_name LIKE ', $s)
                 ->fetch();
     }
     
    // public function 

 }
 