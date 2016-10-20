<?php

 /*
  * Pharo
  */

 namespace Nette\Forms\Controls;

 use Nette\Utils\Html;

 /**
  * Description of PharoSimpleUplad
  *
  * @author hippo
  */
 class PharoSimpleUpload extends UploadControl {
     /*
      * <!-- fancy info -->
       <div class="fancy-file-upload fancy-file-info">
       <i class="fa fa-upload"></i>
       <input type="file" class="form-control" name="contact[attachment]" onchange="jQuery(this).next('input').val(this.value);" />
       <input type="text" class="form-control" placeholder="no file selected" readonly="" />
       <span class="button">Choose File</span>
       </div>
      * 
      * <label for="file" class="input input-file">
                                    <div class="button">
                                        <input type="file" id="file" onchange="this.parentNode.nextSibling.value = this.value">Browse
                                    </div><input type="text" readonly>
                                </label>
      */

     public function getControl() {

         $supper = Html::el('div class="fancy-file-upload fancy-file-info"');
         $i = Html::el('i class="fa fa-upload"');
         $input = parent::getControl();
         $input->addAttributes(['class' => 'form-control']);
         $input->addAttributes(['onchange' => "jQuery(this).next('input').val(this.value);"]);
         $inputAdd = Html::el('input type="text" class="form-control" placeholder="no file selected" readonly=""');
         $span = Html::el('span class="button"')->addText('Choose File');
         $supper->add($i)->add($input)->add($inputAdd)->add($span);
         return $supper;
     }

 }
 