<?php

 namespace Nette\Forms\Controls;

 use Nette\Utils\Html;

 class ImageDialog extends TextBase {

     //put your 7code here

     /* <div class="input-group input-group-sm">
       <input class="form-control" type="text">
       <span class="input-group-btn">
       <button type="button" class="btn btn-info btn-flat">Go!</button>
       </span><button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
	Bootstrap Modal
</button>
       </div> */



     public function getControl($caption = NULL) {
         $supper = Html::el();
         $envelope = Html::el('div class="input-group input-group-sm"');
         $input = parent::getControl();
         $input->class('form-control text');
         $input->addAttributes(['id'=>'ImageID']);
         $input->value = $this->rawValue === '' ? $this->emptyValue : $this->rawValue;
         $span = Html::el('span class="input-group-btn"');
         $button = Html::el('button data-toggle="modal" data-target="#myModal" class="btn  btn-info btn-flat iframe-btn" '
                 . 'type="button"');
         $button->setText('Image Perex');

         $span->add($button);
         $envelope->add($input);
         $envelope->add($span);
         $supper->add($envelope);


         return $supper;
     }

 }

 /*$supper = Html::el();
         $xinput = Html::el('div class="input-group"');
         $input = parent::getControl();
         //$input->class('form-control button-' . $this->getHtmlName());
         //$input->addAttributes(['id'=>'ImageID']);
         $href = Html::el('a href="/pharo/filemanager//dialog.php?type=1&amp;field_id=ImageID&amp;relative_url=1"');
         $href->class("btn iframe-btn");
         $href->addAttributes(['type'=>'button']);
         $href->setText('Image Perex');
         $xinput->add($input);
         $xinput->add($href);
         $supper->add($xinput);*/