<?php

 namespace App\DB;

 use Nette;

 class BlogCategoryModel extends AbstractDBModel {

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
     protected $ordering;

     /**
      *
      * @var type 
      */
     protected $table = 'blog_category';

     /**
      * Function gets max new ordering from rowset
      * @param \Nette\Utils\ArrayHash $data
      */
     protected function setDefaultOrdering(&$data) {
         $data->ordering = $this->getSelection()->max('ordering +1');
     }

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
 