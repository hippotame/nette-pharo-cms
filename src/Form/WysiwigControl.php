<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Nette\Forms\Controls;

use Nette\Utils\Html;

/**
 * Description of WysiwigControl
 *
 * @author hippo
 */
class WysiwigControl extends TextArea {

    //put your code here

    protected $type = 'basic';
    
    protected $menubar = [];
    
    protected $toolbars = [
        'basic' => 'bold, italic, underline, strikethrough, alignleft, '
        . 'aligncenter, alignright, alignjustify, styleselect, formatselect, '
        . 'fontsizeselect | cut, copy, paste, bullist, numlist, outdent, indent, '
        . ' undo, redo, removeformat, subscript, superscript',
    ];

    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    public function getType() {
        return $this->type;
    }

    public function getControl() {

        $super = Html::el();
        $control = Html::el('div')->class('controls');
        $xinput = Html::el('div')->class('xdisplay_inputx form-group');
        $input = parent::getControl();
        $input->class('form-control wysiwig-' . $this->getHtmlName());
        $xinput->add($input);
        $control->add($xinput);
        $super->add($control);
        $super->add($this->addJS());
        return $super;
    }

    protected function addJS() {
        $script = Html::el('script');
        $script->language = 'javascript';

        $script->add(""
                . ""
                . " tinymce.init({"
                . "     selector: 'textarea.wysiwig-" . $this->getHtmlName() . "',"
                . "     language: 'cs_CZ',"
                . "     height: 200,"
                . "     ".$this->setToolbar().""
                . "});");
        return $script;
    }
    
    protected function setToolbar( ) {
        if ( isset( $this->toolbars[$this->getType()]) === false ) {
            return ;
        }
        return sprintf("toolbar:'%s',", $this->toolbars[$this->getType()]);
    }

}
