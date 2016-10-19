<?php

 /*
  * Pharo
  */

 namespace Nette\Forms\Controls;

 use Nette\Utils\Html;

 /**
  * Description of PharoMultiCheckbox
  *
  * @author hippo
  */
 class PharoMultiCheckbox extends CheckboxList {

     //put your code here

     public $options = [];
     public $defaults = [];

     public function addOption($arr) {
         $this->options = $arr;
         $this->setItems($arr);
     }

     public function addDefaults($arr) {
         $this->defaults = $arr;
     }

     public function getHtmlName() {
         return parent::getHtmlName();
     }

     public function setHtmlName($key) {
         $name = str_replace('[]', '', $this->getHtmlName()) . '[' . $key . ']';
         return $name;
     }

     public function getControl() {


         $supper = Html::el('');

         forEach ($this->options as $key => $val) {
             $label = Html::el('label class="checkbox"');
             $input = Html::el('input type="checkox" value="1"');
             $input->addAttributes(['id' => $this->htmlId . '-' . $key]);
             $input->addAttributes(['name' => $this->setHtmlName($key)]);

             $html = Html::el('i');
             $text = Html::el();
             $key_text = str_replace('pharo_', '', $key);
             $text->addText($key_text);
             $label->add($input);
             $label->add($html);
             $label->add($text);
             $supper->add($label);
         }

         //dump( $this );die();
         return $supper;
     }

 }
 