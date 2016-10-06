<?php

namespace Nette\Forms\Controls;

use Nette\Utils\Html;

/**
 * Description of WysiwigControl
 *
 * @author hippo
 */
class WysiwigControl extends TextArea {

    protected $type = 'basic';
    protected $menu = 'basic';
    protected $height = ['basic' => 200, 'full' => 600];
    protected $plugins = '';
    protected $menubars = [
        'basic' => 'edit view format'
    ];
    protected $toolbars = [
        'basic' => ''
            . 'undo, redo, cut, copy, paste, | bold, italic, underline, strikethrough, alignleft, '
            . 'aligncenter, alignright, alignjustify, styleselect, formatselect, '
            . 'fontsizeselect |  bullist, numlist, outdent, indent, '
            . ' subscript, superscript',
        'full' => ''
            . 'undo, redo, cut, copy, paste, | bold, italic, underline, strikethrough, alignleft, '
            . 'aligncenter, alignright, alignjustify, styleselect, formatselect, '
            . 'fontsizeselect ,|  bullist, numlist, outdent, indent, subscript, superscript' 
            . ',| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | preview code'
    ];

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
        $tiny = ''
                . ' tinymce.init({'
                . ' selector: "textarea.wysiwig-'.$this->getHtmlName().'",'
                . ' convert_urls: true,'
                . ' relative_urls: false,'
                . ' theme: "modern",'
                . ' language: "cs_CZ",'
                . $this->setEditorHeight() 
                . $this->setMenuBar()
                . $this->setToolbar()
                . ' external_filemanager_path:"/pharo/filemanager/",
                    filemanager_title:"Responsive Filemanager" ,
                    external_plugins: { "filemanager" : "/pharo//filemanager/plugin.min.js"},'
                . ' image_advtab: true ,'
                . ' plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                        "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
                    ],'
                . '});'
                ;
        
        
        $script->add($tiny);
        return $script;
    }

    protected function setToolbar() {
        if (isset($this->toolbars[$this->getType()]) === false) {
            return sprintf("toolbar:'%s',", $this->toolbars['basic']);
        }
        return sprintf("toolbar:'%s',", $this->toolbars[$this->getType()]);
    }

    protected function setMenuBar() {
        if (isset($this->menubars[$this->getType()]) === false) {
            return sprintf("menubar:'%s',", $this->menubars['basic']);
        }
        return sprintf("menubar:'%s',", $this->menubars[$this->getType()]);
    }

    protected function setEditorHeight() {
        if (isset($this->height[$this->getType()]) === false) {
            return sprintf("menubar:'%s',", $this->menubars['basic']);
        }
        return sprintf("height:'%s',", $this->height[$this->getType()]);
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getType() {
        return $this->type;
    }

    
    public function getPlugins() {
        return $this->plugins;
    }

    public function setPlugins($plugins) {
        $this->plugins = $plugins;
        return $this;
    }

    

}
