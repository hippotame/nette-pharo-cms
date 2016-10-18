<?php
 namespace Nette\Forms\Controls;

use Nette\Utils\Html;


 class PharoCheckbox extends TextBase {
     
     
     
      public function getControl() {

        $super = Html::el();
        $label = Html::el('label class="switch switch-success"');
        $input = Html::el();
        $input = parent::getControl();
        //$input->value = $this->rawValue === '' ? $this->emptyValue : $this->rawValue;
        $span = Html::el('span class="switch-label" data-on="YES" data-off="NO"');
        $input->addAttributes(['type'=>'checkbox']);
        //
        if ( $this->rawValue == 1 ) {
            $this->value = true;
            $input->addAttributes(['checked'=>'checked']);
        }
        
        //dump( $input );
        $label->add($input);
        $label->add($span);
        $super->add($label);
        return $super;
    }
    
    
    public function setDefaultValue($value) {
        parent::setDefaultValue($value);
    }
     
     
 }
 
 /*if ( $this->value === true ) {
            $this->value = 'YES';*/