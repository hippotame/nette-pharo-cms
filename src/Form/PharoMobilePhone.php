<?php

 /*
  * Pharo
  */

 namespace Nette\Forms\Controls;

 use Nette\Utils\Html;

 /**
  * Description of PharoMobilePhone
  *
  * @author hippo
  */
 class PharoMobilePhone extends TextBase {
     /*
      * <!-- Phone Number -->
       <div class="fancy-form"><!-- input -->
       <i class="fa fa-phone-square"></i>

       <!-- replace here any input from below if you want fancy style (icon + tooltip) -->
       <input type="text" class="form-control masked" data-format="(999) 999-999999" data-placeholder="X" placeholder="Enter telephone">

       <span class="fancy-tooltip top-left"> <!-- positions: .top-left | .top-right -->
       <em>Type Your Phone Number</em>
       </span>
       </div>
      */

     public function getControl() {

         $supper = Html::el('div class="fancy-form"');
         $i = Html::el('i class="fa fa-phone-square"');
         $input = parent::getControl();
         $input->addAttributes(['data-placeholde' => 'X']);
         $input->addAttributes(['data-format' => '(+999) 999-999-999']);
         $input->addAttributes(['class' => 'form-control masked']);
         $input->addAttributes(['placeholder' => 'Enter telephone']);
         $span = Html::el('span class="fancy-tooltip top-left"');
         $em = Html::el('em')->addText('Type Your Phone Number');
         $input->value = $this->rawValue === '' ? $this->emptyValue : $this->rawValue;
         $supper->add($i)->add($input);
         $span->add($em);
         $supper->add($span);
         return $supper;
     }

 }
 