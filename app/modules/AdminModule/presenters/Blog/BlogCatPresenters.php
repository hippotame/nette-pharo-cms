<?php
namespace App\AdminModule\Presenters;

use Nette;
use DB\BlogCategoryModel;
use DataTable\DataTable;

class BlogCatPresenter extends BasePresenter
{

    protected $CategoryModel;

    protected $catsHead = [
        'id' => [
            'name' => '#ID',
            'width' => 20
        ],
        'cat' => [
            'name' => 'Name'
        ],
        'edit' => [
            'name' => 'EDIT',
            'width' => 100
        ],
        'delete' => [
            'name' => 'Delete',
            'width' => 100
        ]
    ];

    protected $catsData = [];

    protected function startup()
    {
        parent::startup();
        $this->initCategories();
    }

    public function actionEdit($id = null)
    {}

    private function initCategories()
    {
        $this->CategoryModel = new BlogCategoryModel($this->db);
    }

    public function renderDefault()
    {
        $cats = $this->CategoryModel->getCategories();
        $this->catsData = $cats;
    }

    /*
     * (non-PHPdoc)
     * @see \Nette\ComponentModel\Container::createComponent()
     */
    protected function createComponentDataTable()
    {
        $object = new DataTable();

        $object->addHeads($this->catsHead);
        $object->setDataSource($this->catsData);
        return $object;
    }
}