<?php

 namespace App\BlogModule\Presenters;

 use Nette\Application\UI\Form;
 use Nette\Database\SqlLiteral;
 use App\DB\BlogCategoryModel;
 use App\DB\BlogPostsModel;
 use App\DB\BlogCommentsModel;

 class BlogPresenter extends BasePresenter {

     protected $model;
     protected $catModel;
     protected $commentsModel;
     
     protected $cat = null;

     public function startup() {
         parent::startup();
         $this->model = new BlogPostsModel($this->db);
         $this->catModel = new BlogCategoryModel($this->db);
         $this->commentsModel = new BlogCommentsModel($this->db);
     }
     
     public function actionDefault($id){
         $this->cat = $id;
     }

     public function renderDefault() {

         $data = $this->model->loadFront(1, 1, 10, $this->cat);
         $this->template->posts = $data;
     }

     public function actionRead($id) {

         $post = $this->model->load(1, $id);
         $category = $this->catModel->load(1, $post->id_blog_category);
         $this->template->post = $post;
         $this->template->category = $category;
         $this->id = $id;
         $this->template->isBasicAuth = $this->isBasicAuth();
         $this->template->commentAuthor = $this->getAuthorName($id);
         $comments = [];
         
         $this->template->commentCount = $this->commentsModel->getCountForBlog($id);
         if ( $this->template->commentCount > 0 ) {
             
         } 
     }
     
     public function getAuthorName($id) {
         $model = new \App\DB\UserModel($this->db);
         return $model->getUserName($id);
     }
     
     public function getCommentCount($id) {
         return $this->commentsModel->getCountForBlog($id);
     }

     public function createComponentCommentForm() {
         $form = new Form();
         $form->setTranslator($this->translator);
         $form->addText('title', 'Title');
         $form->addTextArea('message', '', null, 10)->setRequired('Post cannot be empty!');
         $form->addSubmit('save', 'Submit Message')->setAttribute('class', 'btn btn-primary');
         $form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
         $form->addHidden('id_parent', $this->id);
         $form->addHidden('id_user', \Pharo\XorCrypt::encrypt($this->user->id));
         $form->onSuccess[] = $this->commentSuccess;
         return $form;
     }

     public function commentSuccess(Form $form) {
         
         $data = $form->getValues(1);
         $error = false;
         if ( empty ( $data['message']) === true ) {
             $error = true;
             $form['message']->addError('Post cannot be empty!');
         }
         if ( $error === false ) {
             $data['date_created'] = new SqlLiteral('NOW()');
             $data['id_user'] = \Pharo\XorCrypt::decrypt($data['id_user']);
             $this->db->table('blog_comments')->insert($data);
             $this->redirect('read', $this->id);
         }
     }

 }
 