<?php

 namespace App\ProfileModule\Presenters;

 use Nette;
 use App\CommonModule\Presenters\CommonPresenter;

 class BasePresenter extends CommonPresenter {
     
     
     
     public function startup() {
         parent::startup();
        // dump( $this->user );
     }

    
 }
 