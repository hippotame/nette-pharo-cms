<?php

 /*
  * Pharo
  */

 namespace App\DB;

 /**
  * Description of ForumPostModel
  *
  * @author hippo
  */
 class ForumPostModel extends AbstractDBModel {

     public $table = 'forum_post';
     
     


     public function loadAll() {

         $count = $this->getSelection()->count();

         $sql = ' 
       
                
                
                
                
                ';
     dump ( $count );
         
     }

 }
 