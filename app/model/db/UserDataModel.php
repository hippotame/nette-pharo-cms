<?php

 /*
  * Pharo
  */

 namespace App\DB;

 /**
  * Description of UserDataModel
  *
  * @author hippo
  */
 class UserDataModel extends AbstractDBModel {

     public $table = 'users_data';

     public function load($lang = 1, $id=null) {
         $data = $this->getSelection()->where('id_user',$id)->fetch();
         if( empty( $data ) ) {
             $this->getSelection()->insert(['id_user'=>$id]);
         }
         return $data;
      }

 }
 