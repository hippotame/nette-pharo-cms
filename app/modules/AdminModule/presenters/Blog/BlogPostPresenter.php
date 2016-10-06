<?php

namespace App\AdminModule\Presenters;

use Nette;
use DB\BlogPostsModel;
use DB\BlogCategoryModel;
use Nette\Application\UI\Form;

class BlogPostPresenter extends BasePresenter {

    protected $catModel;
    protected $id;
    protected $data;

    protected function startup() {
        parent::startup();
        $this->model = new BlogPostsModel($this->db);
        $this->catModel = new BlogCategoryModel($this->db);
    }

    public function renderDefault() {
        if ($this->model->count() < 1) {
            $this->redirect('edit');
        }
    }

    public function actionEdit($id = null) {
        if (is_null($id) === false) {
            
        }
    }

    /*
     * (non-PHPdoc)
     * @see \Nette\ComponentModel\Container::createComponent()
     */

    protected function createComponentEdit() {
        $form = new Form();
        $form->addMultiSelect('category', 'Category', $this->catModel->getForSelect())->setRequired();
        $datetime = new Nette\Forms\Controls\DateTimePickerControl('Zobrazit dne');
        $form->addComponent($datetime, 'date_release');
        $form->addText('post_title', 'Title')->setRequired();
        $form->addSelect('post_status', 'Status', [
            'publish' => 'Publish',
            'hide' => 'Hide'
        ])->setPrompt('Publish or not')->setRequired();
        $perex = new \Nette\Forms\Controls\WysiwigControl('Perex');
        $form->addComponent($perex, 'post_perex');
        $content = new Nette\Forms\Controls\WysiwigControl('Body');
        $content->setType('full');
        $form->addComponent($content, 'post_content');


        $form->addSubmit('send', 'Save Post'); //->setAttribute('class', 'btn btn-success');

        $form->onValidate[] = $this->editValidate;
        $form->onSuccess[] = $this->editSuccessed;


        $this->bootstrapize($form);


        return $form;
    }

    public function editValidate(Form $form) {
        $data = $form->getValues();
    }

    public function editSuccessed(Form $form) {
        $data = $form->getValues();
    }

}
