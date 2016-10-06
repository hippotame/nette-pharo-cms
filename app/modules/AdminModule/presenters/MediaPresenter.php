<?php
namespace App\AdminModule\Presenters;

use Nette;
use MediaManager\MediaManagerModel;

class MediaPresenter extends BasePresenter
{

    protected $path;
    
   
    
    
    public function beforeRender() {
        parent::beforeRender();
        
    }

    public function actionDefault($mode = 'page')
    {
        
    }
}