<?php

 /*
  * Pharo
  */

 namespace App\AdminModule\Presenters;

 use Nette;
 use App\DB\PageCategoryModel;
 use DataTable\DataTable;
 use Nette\Application\UI\Form;

 /**
  * Description of PageCatPresenter
  *
  * @author hippo
  */
 class PageCatPresenter extends BasePresenter {

     protected $model;
     protected $data;

     /**
      *
      * @var type 
      */
     protected $catsHead = [
         'id'     => [
             'name'  => '#ID',
             'width' => 20
         ],
         'parent' => [
             'name'      => 'Parent',
             'transform' => 'getCatParent',
         ],
         'name'   => [
             'name' => 'Name'
         ],
         'edit'   => [
             'name'  => 'EDIT',
             'width' => 100
         ],
         'delete' => [
             'name'  => 'Delete',
             'width' => 100
         ]
     ];
     protected $catsData = [];

     //put your code here

     protected function startup() {
         parent::startup();
         $this->init();
     }

     protected function init() {
         parent::init();
         $this->model = new PageCategoryModel($this->db);
     }

     public function renderDefault() {
         $cats = $this->model->load($this->lang);
         $this->catsData = $cats;
     }

     /**
      * 
      * @param id $id
      */
     public function actionEdit($id = null) {
         if ($id == 1) {
             $this->flashMessage('You cant edit default category.', 'danger');
             $this->redirect('PageCat:');
         }
         if (is_null($id) === false) {
             $this->id = $id;
             $data = $this->model->load($this->lang, $id);
             $this->data = $data;
         }
     }

     public function getCatParent($value) {
         if ($value == '0') {
             return 'top';
         }
         $result = $this->model->load($this->lang, $value);
         return '(' . $value . ') - ' . $result->name;
     }

     /**
      * 
      * @param type $id
      * @throws Nette\Neon\Exception
      */
     public function actionDelete($id) {
         if (is_null($id) === true) {
             throw new Nette\Neon\Exception('ID is not set possible hack');
         }
         $this->model->delete($id);
         $this->flashMessage('Item deleted successfully', 'success');
         $this->redirect('default');
     }

     /*
      * (non-PHPdoc)
      * @see \Nette\ComponentModel\Container::createComponent()
      */

     protected function createComponentEdit() {
         $form = new Form();
         $form->addHidden('name_txt', '');
         $form->addSelect('parent', 'Parent Cat', $this->model->getForSelect());
         $form->addText('name', 'Category name')->setRequired();
         $form->addSubmit('send', 'Save Post'); //->setAttribute('class', 'btn btn-success');

         $form->onValidate[] = $this->editValidate;
         $form->onSuccess[] = $this->editSuccessed;


         $this->bootstrapize($form);

         if (is_null($this->id) === false) {
             // is edit 
             $form->addHidden('id', $this->id);
             $defaults['name_txt'] = $this->data->name_txt;
             $defaults['name'] = $this->data->name;
             $form->setDefaults($defaults);
         }
         return $form;
     }

     /**
      * 
      * @param Form $form
      */
     public function editValidate(Form $form) {
         $data = $form->getValues();
         //dump( $data ); die();
         if (empty($data->name) === true) {
             $form['name']->addError('You must select name of category');
         }
     }

     /**
      * 
      * @param Form $form
      */
     public function editSuccessed(Form $form) {
         $data = $form->getValues();
         //dump( $data ); die();
         if ($data->parent == '1') {
             $data->parent = 0;
         }
         $this->model->store($data);
         $this->flashMessage('Category edited successfully', 'success');
         $this->redirect('PageCat:');
     }

     /*
      * (non-PHPdoc)
      * @see \Nette\ComponentModel\Container::createComponent()
      */

     protected function createComponentDataTable() {
         $object = new DataTable();
         $object->addHeads($this->catsHead);
         $object->setDataSource($this->catsData);
         return $object;
     }

 }
 