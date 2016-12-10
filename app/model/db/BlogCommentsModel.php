<?php

 /*
  * Pharo
  */

 namespace App\DB;

 /**
  * Description of BlogCommentsModel
  *
  * @author hippo
  */
 class BlogCommentsModel extends AbstractDBModel {

     /**
      *
      * @var type 
      */
     protected $id;

     /**
      *
      * @var type 
      */
     protected $table = 'blog_comments';

     public function getCommentsForBlog($id) {
         return $this->getSelection()->where('id_parent', $id)->fetchAll();
     }

     public function getCountForBlog($id) {
         return $this->getSelection()->where('id_parent', $id)->count();
     }

 }
 