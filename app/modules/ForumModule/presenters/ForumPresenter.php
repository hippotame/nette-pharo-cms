<?php

 /*
  * Pharo
  */

 namespace App\ForumModule\Presenters;

 /**
  * Description of ForumPresenter
  *
  * @author hippo
  */
 class ForumPresenter extends BasePresenter {
     //put your code here
     
     
     public function beforeRender() {
         parent::beforeRender();
         $this->restrictAccess(100);
     }
     
     public function renderDefault(){
         
         
         
         
         
     }
     
     
 }
 