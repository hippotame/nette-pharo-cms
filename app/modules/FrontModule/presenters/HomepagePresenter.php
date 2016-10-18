<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model;


class HomepagePresenter extends BasePresenter
{
    
    public function startup() {
        parent::startup();
        
        if( $this->getModule() == 'Front' ) {
            $this->redirect(':Blog:Homepage:default');
        }
        
    }

	public function renderDefault()
	{
            //die();
		$this->template->anyVariable = 'any value';
	}

}
