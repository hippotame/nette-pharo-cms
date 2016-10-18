<?php

 /*
  * To change this license header, choose License Headers in Project Properties.
  * To change this template file, choose Tools | Templates
  * and open the template in the editor.
  */

 namespace Nette\Forms\Controls;

 use Nette\Utils\Html;

 /**
  * Description of PharoSelect
  *
  * @author hippo
  */
 class PharoSelect extends SelectBox {

     //put your code here
     public function getControl() {

         $supper = Html::el();
         $control = Html::el('div')->class('');
         //$control = Html::el();
         $input = parent::getControl();
         $input->class('form-control');
         $input->addAttributes(['placeholder' => 'Select item']);
         $control->add($input);
         $supper->add($control);
         $script = Html::el('script');
         $script->language = 'javascript';
         $script->add(' ');
         $supper->add($script);
         return $supper;
     }

 }
 