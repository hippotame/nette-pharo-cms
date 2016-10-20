<?php

 namespace App\BlogModule\Presenters;



 class BlogPresenter extends BasePresenter {

     protected $model;
     protected $catModel;

     public function startup() {
         parent::startup();
         $this->model = new \App\DB\BlogPostsModel($this->db);
         $this->catModel = new \App\DB\BlogCategoryModel($this->db);
     }

     public function renderDefault() {

         $data = $this->model->loadFront(1, 1, 10);
         //dump($data);
         $this->template->posts = $data;
     }

     public function actionRead($id) {
         
         $post = $this->model->load(1, $id);
         $category = $this->catModel->load(1,$post->id_blog_category);
         $this->template->post = $post;
         $this->template->category = $category;
         //dump( $category );
         //dump( $post );
     }
     
     
     

 }
 