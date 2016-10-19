<?php

 namespace App\DB;

 /**
  * Description of UserModule
  *
  * @author hippo
  */
 class UserRightsModel extends AbstractDBModel {

     /**
      *
      * @var type 
      */
     protected $id;
     protected $table = 'users_rights';

     public function insertRights($id, $insert) {
         $this->getSelection()->where('id_user', $id)->delete();
         $this->getSelection()->insert($insert);
     }

 }
 