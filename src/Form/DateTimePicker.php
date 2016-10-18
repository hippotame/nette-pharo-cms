<?php

namespace Nette\Forms\Controls;

use Nette\Utils\Html;

class DateTimePickerControl extends TextBase {

    public function getControl() {

        $super = Html::el();
        $control = Html::el('div')->class('input-group date');
        $xinput = Html::el('div')->class('input-group-addon');
        $input = parent::getControl();
        $input->class('form-control pull-right datepicker datetimepicker-' . $this->getHtmlName());
        $input->addAttributes(['data-format'=>'dd/mm/yyyy']);
        $input->value = $this->rawValue === '' ? $this->emptyValue : $this->rawValue;
        $span = Html::el('span')->class('fa fa-calendar');
        $xinput->add($span);
        $control->add($xinput);
        $control->add($input);
        $super->add($control);
        return $super;
    }

}
