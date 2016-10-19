<?php

 namespace App\BlogModule\Presenters;

 use Nette;
 use DB\BlogCategoryModel;

 class BlogPresenter extends BasePresenter {

     protected $model;

     public function startup() {
         parent::startup();
         $this->model = new \App\DB\BlogPostsModel($this->db);
     }

     public function renderDefault() {

         $data = $this->model->loadFront(1, 1, 10);
         //dump($data);
         $this->template->posts = $data;
     }

     public function actionRead($id) {
         
     }
     
     
     public function getCategory($id) {
         $model = new \App\DB\BlogCategoryModel($this->db);
         
     }

 }
 