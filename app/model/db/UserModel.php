<?php

 namespace App\DB;

 /**
  * Description of UserModule
  *
  * @author hippo
  */
 class UserModel extends AbstractDBModel {
     /**
      *
      * @var type 
      */
     protected $id;

     
     protected $table = 'users';
     
     
     public function getUser($id) {
         return $this->getSelection()->where('id',$id)->fetch();
     }
     
     public function getUserName( $id ) {
         if ( $this->getUser($id)->display_name == '' ){
             return $this->getUser($id)->user_login;
         }
         return $this->getUser($id)->display_name;
     }
 }
 
 