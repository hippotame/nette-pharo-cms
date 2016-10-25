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


     protected $model;
     protected $totalPosts;
     protected $postDefaults = [];

     protected function startup() {
         parent::startup();
         $this->model = new \App\DB\ForumPostModel($this->db);
     }

     public function beforeRender() {
         parent::beforeRender();
         $this->restrictAccess(100);
     }

     public function getUserDataForForum($id) {
         $posts = [];
         $user = [];
         $userData = [];
         $user = $this->db->table('users')->where('id', $id)->fetch()->toArray();
         $userData = $this->db->table('users_data')->where('id_user', $id)->fetch()->toArray();
         unset($user['user_pass']);
         unset($user['user_activation_key']);
         if (empty($user['display_name'])) {
             $user['display_name'] = $user['user_login'];
         }
         $posts['count_posts'] = $this->db->table('forum_post')->where('id_user', $id)->count();
         return $user + $userData + $posts;
     }

     public function renderDefault($page = 1, $items = 0) {

         $this->template->user = $this->getUserDataForForum($this->user->getId());
         $paginator = new \Nette\Utils\Paginator;
         $paginator->setItemsPerPage(30);
         $paginator->setPage($page);

         $posts = $this->db->table('forum_post')
                         ->order('date_release DESC')
                         ->limit($paginator->getLength(), $paginator->getOffset())->fetchAll();
         //dump ( $posts );
         if ($items == 0) {
             $this->totalPosts = $this->db->table('forum_post')->count();
         } else {
             $this->totalPosts = $items;
         }
         $paginator->setItemCount($this->totalPosts);

         $this->template->totalPosts = $paginator->getPageCount();
         $this->template->posts = $posts;
         $this->template->page = $paginator->page;

         $this->getCommand();
     }

     public function actionSearch($s, $page = 1, $items = 0) {
         $s = \Nette\Utils\Strings::normalize($s);
         //vyhod prazdne 
         if (empty($s) === true) {
             $this->flashMessage('Search string must not be empty', 'danger');
             $this->redirect('default');
         }
         $search = new \App\Model\Search($this->db);
         //search users
         $users = $search->SearchUser($s);
         if (empty($users) === false) {
             $paginator = new \Nette\Utils\Paginator;
             $paginator->setItemsPerPage(30);
             $paginator->setPage($page);
             $posts = $this->db->table('forum_post')
                             ->where('id_user', $users->id)
                             ->order('date_release DESC')
                             ->limit($paginator->getLength(), $paginator->getOffset())->fetchAll();
         }
         //$this->db->table('users')->

         echo 'working on it';
         dump($s);
         die();
     }

     private function getCommand() {
         $query = $this->getHttpRequest()->getQuery();
         if (empty($this->getHttpRequest()->getQuery('command')) === false) {
             if ($this->getHttpRequest()->getQuery('command') == 'answer') {
                 if (null === $this->getHttpRequest()->getQuery('answer_id')) {
                     return;
                 }
                 $post = $this->model->load(1, $this->getHttpRequest()->getQuery('answer_id'));
                 $this->postDefaults['parent_id_post'] = $post->id;
                 $post->post_content = str_replace('<blockquote>', '', $post->post_content);
                 $post->post_content = str_replace('</blockquote>', '', $post->post_content);
                 $this->postDefaults['post_content'] = sprintf('<blockquote>%s</blockquote><br /><br />', $post->post_content);
             }
         }
     }

     public function createComponentAddPostForm() {
         $form = new \Nette\Application\UI\Form();
         $form->getElementPrototype()->class('block-review-content');
         $form->addTextArea('post_content', '')
                 ->setAttribute('class', 'summernote form-control')
                 ->setAttribute('data-height', 200)
                 ->setAttribute('data-lang', 'cs-CZ')
                 ->setRequired('Post cannot be empty!');
         $form->addSubmit('save', 'Save Post')->setAttribute('class', 'btn btn-primary');
         $form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
         $form->onSuccess[] = $this->savePost;
         $form->addHidden('id_user', $this->user->id);
         $parent_id_post = New \Nette\Database\SqlLiteral('NULL');
         $form->addHidden('parent_id_post', $parent_id_post);
         $form->addHidden('user_attitude', 1);
         $form->setDefaults($this->postDefaults);
         return $form;
     }

     public function savePost(\Nette\Application\UI\Form $form) {
         $data = $form->getValues();
         $data->date_release = new \Nette\Database\SqlLiteral('NOW()');
         $this->model->store($data, 1);
         $this->redirect('default#forum_top');
     }

 }
 