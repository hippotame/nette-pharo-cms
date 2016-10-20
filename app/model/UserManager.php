<?php

 namespace App\Model;

 /**
  * Users management.
  */
 class UserManager {

     public $id;
     public $database;
     public $model;
     public $modelData;

     public function __construct(\Nette\Database\Context $database, $id = null) {
         if (is_null($id) === true) {
             throw new \ErrorException('User id is not set');
         }
         $this->id = $id;
         $this->database = $database;
         $this->model = new \App\DB\UserRightsModel($this->database);
         $this->modelData = new \App\DB\UserDataModel($this->database);
     }

     public function setRights() {
         $rights = $this->model->loadByUser($this->id);
         if (empty($rights) === true) {
             return '';
         }
         $data = [];
         forEach ($rights as $key => $row) {
             $int = $row['pharo_read'] . $row['pharo_write'] . $row['pharo_admin'];
             $data[$row['module']] = (int) $int;
         }
         return $data;
     }

     public function getData() {
         return $this->modelData->load(1,$this->id);
     }

 }
 