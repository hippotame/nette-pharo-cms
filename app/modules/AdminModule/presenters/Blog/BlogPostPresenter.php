<?php

 namespace App\AdminModule\Presenters;

 use Nette;
 use DB\BlogPostsModel;
 use DB\BlogCategoryModel;
 use Nette\Application\UI\Form;
 use DataTable\DataTable;

 class BlogPostPresenter extends BasePresenter {

     protected $catModel;
     protected $data;
     protected $heads = [
         'id'           => [
             'name'  => '#ID',
             'width' => 20
         ],
         'date_release' => [
             'name'      => 'Date to Release',
             'width'     => 50,
             'transform' => 'toEditor'
         ],
         'post_title'   => [
             'name'  => 'Title',
             'width' => 150
         ],
         'post_perex'   => [
             'name'      => 'Perex',
             'transform' => 'cutPerex'
         ],
         'edit'         => [
             'name'  => 'EDIT',
             'width' => 100
         ],
         'delete'       => [
             'name'  => 'Delete',
             'width' => 100
         ]
     ];
     protected $datas = [];

     protected function startup() {
         parent::startup();
         $this->model = new BlogPostsModel($this->db);
         $this->catModel = new BlogCategoryModel($this->db);
     }

     public function renderDefault() {
         if ($this->model->count() < 1) {
             $this->redirect('edit');
         }
         $this->datas = $this->model->load($this->lang);
     }

     public function actionEdit($id = null) {
         if (is_null($id) === false) {
             $this->id = $id;
             $data = $this->model->load($this->lang, $id);
             $this->data = $data;
         }
     }

     /*
      * (non-PHPdoc)
      * @see \Nette\ComponentModel\Container::createComponent()
      */

     protected function transColumn(Form &$form) {
         //dump( $this->data );
         $defaults = [];
         forEach ($this->data as $key => $row) {
             $defaults[$key] = $row;
         }
         forEach ($this->data as $key => $row) {
             if (preg_match('/\_txt/i', $key)) {
                 $id_name = str_replace('_txt', '', $key);
                 $form->addHidden($id_name, $this->data->$key);
                 $defaults[$key] = $this->data->$id_name;
                 $defaults[$id_name] = $this->data->$key;
             }
         }
         return $defaults;
     }

     protected function createComponentEdit() {
         $form = new Form();
         /* $multiselect = new Nette\Forms\Controls\PharoMultipleSelect('Category');
           $multiselect->setItems($this->catModel->getForSelect());
           $form->addComponent($multiselect, 'id_blog_category'); */
         $select = new Nette\Forms\Controls\PharoSelect('Category');
         $select->setItems($this->catModel->getForSelect());
         $form->addComponent($select, 'id_blog_category');
         $datetime = new Nette\Forms\Controls\DateTimePickerControl('Release date');
         $form->addComponent($datetime, 'date_release');
         $datetime = new Nette\Forms\Controls\DateTimePickerControl('Close date');
         $form->addComponent($datetime, 'date_closed');
         $form->addTextArea('post_header_txt', 'Meta Headers tags')
                 ->setAttribute('class', 'form-control')
                 ->setAttribute('placeholder', 'word,by, word or sententions ......');
         $form->addText('post_title_txt', 'Title')->setRequired();
         $form->addSelect('post_status', 'Status', [
             'publish' => 'Publish',
             'hide'    => 'Hide'
         ])->setPrompt('Publish or not')->setRequired();
         $form->addCheckbox('post_can_comment', 'Use discusion with post', '1');
         $perex = new \Nette\Forms\Controls\WysiwigControl('Perex');
         $form->addComponent($perex, 'post_perex_txt');
         $content = new Nette\Forms\Controls\WysiwigControl('Body');
         $content->setType('full');
         $form->addComponent($content, 'post_content_txt');

         if (is_null($this->id) === false) {
             $form->addHidden('id', $this->id);
             $defaults = $this->transColumn($form);
             $form->setDefaults($defaults);
         }
         $form->addSubmit('send', 'Save Post'); //->setAttribute('class', 'btn btn-success');

         $form->onValidate[] = $this->editValidate;
         $form->onSuccess[] = $this->editSuccessed;


         $this->bootstrapize($form);


         return $form;
     }

     /**
      * 
      * @param Form $form
      */
     public function editValidate(Form $form) {
         $data = $form->getValues();
         //dump( $data ); die();
         if (empty($data->id_blog_category) === true) {
             $form['id_blog_category']->addError('You must select a category');
         }
         if (empty($data->date_release) === true) {
             $form['date_release']->addError('You must add Release date. Even past date. You can allways disable viewing by select hide in status');
         }
         if (empty($data->post_title_txt) === true) {
             $form['post_title_txt']->addError('You must add Title');
         }
         if (empty($data->post_status) === true) {
             $form['post_status']->addError('You must Select status');
         }
         if (empty($data->post_perex_txt) === true) {
             $form['post_perex_txt']->addError('You must add perex');
         }
     }

     /**
      * 
      * @param Form $form
      */
     public function editSuccessed(Form $form) {
         $data = $form->getValues();

         $data->date_release = $this->fromEditor($data->date_release);
         $data->date_closed = empty($data->date_closed) === false ? $this->fromEditor($data->date_closed) : null;
         $this->model->store($data);
         $this->flashMessage('Post edited successfully', 'success');
         $this->redirect('BlogPost:');
     }

     /*
      * (non-PHPdoc)
      * @see \Nette\ComponentModel\Container::createComponent()
      */

     protected function createComponentDataTable() {
         $object = new DataTable();
         $object->addHeads($this->heads);
         $object->setDataSource($this->datas);
         return $object;
     }

 }
 