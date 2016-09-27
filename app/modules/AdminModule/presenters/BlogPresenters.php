<?php
namespace App\AdminModule\Presenters;

use Nette;
use DB\BlogCategoryModel;

class BlogPresenter extends BasePresenter {

    protected $CategoryModel;






    private function initCategories() {
        $this->CategoryModel = new BlogCategoryModel($this->db);
    }

    public function renderCategories() {

    }




}