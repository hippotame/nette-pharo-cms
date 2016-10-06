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
        $form->addMultiSelect('category', 'Category', $this->catModel->getForSelect()); //->setRequired();
        $datetime = new Nette\Forms\Controls\DateTimePickerControl('Release Date');
        $form->addComponent($datetime, 'date_release');
        $form->addText('post_title', 'Title')->setRequired();
        $form->addSelect('post_status', 'Status', [
            'publish' => 'Publish',
            'hide' => 'Hide'
        ])->setPrompt('Publish or not')->setRequired();
        $form->addCheckbox('post_can_comment', 'Use discusion with post', '1');
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
        dump( $data ); die();
        if (empty($data->category) === true) {
            $form['category']->addError('You must select a category');
        }
        if (empty($data->date_release) === true) {
            $form['date_release']->addError('You must add Release date. Even past date. You can allways disable viewing by select hide in status');
        }
        if (empty($data->post_title) === true) {
            $form['post_title']->addError('You must add Title');
        }
         if (empty($data->post_status) === true) {
            $form['post_status']->addError('You must Select status');
        }
        if ( empty( $data->post_perex) === true ) {
            $form['post_perex']->addError('You must add perex');
        }
    }

    public function editSuccessed(Form $form) {
        $data = $form->getValues();
    }

}
