<?php

 namespace App\BlogModule\Presenters;

 use Nette;
 use App\CommonModule\Presenters\CommonPresenter;

 class BasePresenter extends CommonPresenter {
     
     
     
     public function startup() {
         parent::startup();
        // dump( $this->user );
     }

     public function createComponentBlogCategories() {
         $cats = new \App\BlogModule\Components\CategoriesControl();
         $cats->setDb($this->db);
         $cats->setCatObj(new \App\DB\BlogCategoryModel($this->db));
         //dump( $cats ); die();
         return $cats;
     }

 }
 