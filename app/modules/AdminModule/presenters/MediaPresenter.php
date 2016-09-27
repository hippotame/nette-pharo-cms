<?php
namespace App\AdminModule\Presenters;

use Nette;
use MediaManager\MediaManagerModel;

class MediaPresenter extends BasePresenter
{

    protected $path;

    public function actionDefault($mode = 'page')
    {
        $manager = new MediaManagerModel($this->db, __STORAGE__);

        $tree = $manager->getTree();

        _print_r( $tree ); die();
    }
}