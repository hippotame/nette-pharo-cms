<?php

 /*
  * Pharo
  */

 namespace App\BlogModule\Components;

 use Nette\Application\UI;

 /**
  * Description of CategoriesControl
  *
  * @author hippo
  */
 class CategoriesControl extends UI\Control {

     protected $db;
     protected $type = null;
     public static $typeCategory = 'category';
     public static $typeStructure = 'structure';
     private $cats;

     public function setType($type) {
         $this->type = $type;
     }

     public function setDb(&$db) {
         $this->db = $db;
     }

     public function setCatObj(\App\DB\BlogCategoryModel $obj) {
         $this->cats = $obj;
     }

     public function render() {
         $template = $this->template;
         $postModel = new \App\DB\BlogPostsModel($this->db);

         $data = $this->cats->load(1);
         $tpl_data = [];
         forEach ( $data as $key => $row ) {
             if( $row->id == 1 ) {
                 continue;
             }
             $d['id'] = $row->id;
             $d['count'] =  $postModel->countIn($row->id);
             $d['name'] = $row->name;
             $tpl_data[] = $d;
         }
         
         $template->tpl_data = $tpl_data;
         $template->setFile(__TPL__ . '/pharocom/Blog/components/blogCategories.latte');

         $template->render();
     }

 }
 