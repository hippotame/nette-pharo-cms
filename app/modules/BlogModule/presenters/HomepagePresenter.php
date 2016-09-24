<?php
namespace App\BlogModule\Presenters;


use Nette;
use DB\BlogCategoryModel;

class HomepagePresenter extends BasePresenter {


    public function renderDefault() {
        $this->template->anyVariable = '';

        $dbModel = new BlogCategoryModel($this->db);
        $data = $dbModel->getCategories();
    }


}