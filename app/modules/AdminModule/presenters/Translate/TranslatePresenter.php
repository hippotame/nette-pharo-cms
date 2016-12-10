<?php

 namespace App\AdminModule\Presenters;

 use Nette;

 class TranslatePresenter extends BasePresenter {

     protected $model;
     protected $data;
     protected $heads = [
         'id'       => [
             'name'  => '#ID',
             'width' => 150
         ],
         'value'    => [
             'name'  => 'Hodnota',
             'width' => 150,
         ],
         'plural_2' => [
             'name'  => 'Plural 1',
             'width' => 150
         ],
         'plural_5' => [
             'name'  => 'Plural 2',
             'width' => 150
         ],
         'edit'     => [
             'name'  => 'EDIT',
             'width' => 100
         ],
         'delete'   => [
             'name'  => 'Delete',
             'width' => 100
         ]
     ];
     protected $datas = [];

     protected function init() {
         parent::init();
         $this->model = new \App\DB\I18nModel($this->db);
         $this->languages_list = [1];
         $this->languages_names = ['CZ'];
     }

     public function renderDefault() {

         $this->redirect('list', 1);
     }

     public function actionList($id) {
         $return = $this->model->load($id);
         //dump ( $return); die();
         $this->datas = $return;
         $this->pers_params = '&lang=' . $id;
     }

     public function actionEdit($id = null) {
         
     }

     public function actionDashboard() {
         
     }

     /*
      * (non-PHPdoc)
      * @see \Nette\ComponentModel\Container::createComponent()
      */

     protected function createComponentEdit() {
         $form = new Nette\Application\UI\Form();

         $form->addText('id', 'ID')->setDisabled()->setRequired();
         $form->addText('value', 'Hodnota');
         $form->addText('plural_2', 'Plural 1');
         $form->addText('plural_5', 'Plural 2');
         $form->addSubmit('send', 'Ulozit'); //->setAttribute('class', 'btn btn-success');

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
     public function editSuccessed(Form $form) {
         $data = $form->getValues();
         $this->model->store($data);
         $this->flashMessage('Category edited successfully', 'success');
         $this->redirect('BlogCat:');
     }

 }
 