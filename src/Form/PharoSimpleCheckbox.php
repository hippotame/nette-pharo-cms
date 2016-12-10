<?php

 /*
  * Pharo
  */

 namespace Nette\Forms\Controls;

 /**
  * Description of PharoSimpleCheckbox
  *
  * @author hippo
  */
 use Nette\Utils\Html;
 
 class PharoSimpleCheckbox extends Checkbox {

     //put your code here


     public function getControl() {
         $super = Html::el();

         $input = parent::getControl();
        // $input->value = $this->rawValue === '' ? $this->emptyValue : $this->rawValue;

         $input->addAttributes(['type' => 'checkbox']);
         //
         /* if ( $this->rawValue == 1 ) {
           $this->value = true;
           $input->addAttributes(['checked'=>'checked']);
           } */

         $super->add($input);
         return $super;
     }
     
     /**
	 * @return Nette\Utils\Html
	 */
	public function getLabelPart()
	{
		return Html::el();
	}

 }
 