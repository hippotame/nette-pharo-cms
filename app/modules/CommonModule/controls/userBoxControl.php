<?php

 /*
  * To change this license header, choose License Headers in Project Properties.
  * To change this template file, choose Tools | Templates
  * and open the template in the editor.
  */

 namespace App\CommonModule\Components;

 use Nette\Application\UI;

 class UsrboxControl extends UI\Control {

     protected $db;
     private $map;
     private $userObj;
     private $user;
     private $translator;

     public function setDb(&$db) {
         $this->db = $db;
     }

     public function setUsersObj(\App\DB\UserModel $obj) {
         $this->userObj = $obj;
     }

     public function setUser($user) {
         $this->user = $user;
     }
     public function setTranslator($obj) {
         $this->translator = $obj;
     }
     

     public function render() {

         $template = $this->template;
         $template->setTranslator($this->translator);

         if ($this->user->isLoggedIn()) {
             $template->setFile(__TPL__ . '/pharocom/Common/components/UserLoggedComponent.latte');
             $template->user = $this->user->identity->data;
             //dump( $template->user );die();
         } else {
             $template->setFile(__TPL__ . '/pharocom/Common/components/UserNotLoggedComponent.latte');
         }

         $template->render();
     }

 }
 