<?php

 namespace App\DB;

 use Nette;

 class PageCategoryModel extends AbstractDBModel {

     /**
      *
      * @var type 
      */
     protected $id;

     /**
      *
      * @var type 
      */
     protected $name_txt;

     

     /**
      *
      * @var type 
      */
     protected $table = 'page_category';

     

     /**
      * 
      * @return type
      */
     public function getForSelect() {
         $res = $this->load();
         $select = [];
         forEach ($res as $k => $v) {
             $select[$v['id']] = $v['name'];
         }
         return $select;
     }
     
     
     
     
     
    

 }
 