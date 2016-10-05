<?php
namespace App\AdminModule\Presenters;

use Nette;
use DB\BlogPostsModel;
use DB\BlogCategoryModel;
use Nette\Application\UI\Form;

class BlogPostPresenter extends BasePresenter
{

    protected $catModel;

    protected function startup()
    {
        parent::startup();
        $this->model = new BlogPostsModel($this->db);
        $this->catModel = new BlogCategoryModel($this->db);
    }

    public function renderDefault()
    {
        if ($this->model->count() < 1) {
            $this->redirect('edit');
        }
    }

    public function actionEdit($id)
    {}

    /*
     * (non-PHPdoc)
     * @see \Nette\ComponentModel\Container::createComponent()
     */
    protected function createComponentEdit()
    {
        $form = new Form();
        $form->addSelect('category','Category',$this->catModel->getForSelect())->setAttribute('class','form-control');
        $datetime = new Nette\Forms\Controls\DateTimePickerControl('Zobrazit dne');
        $form->addComponent($datetime, 'date_release');
        $form->addText('post_title','Title')->setAttribute('class','form-control');
        $perex = new \Nette\Forms\Controls\WysiwigControl('Perex');
        $form->addComponent($perex, 'post_perex');
        $this->bootstrapize($form);
        return $form;
    }
}