<?php

namespace Nette\Forms\Controls;

use Nette\Utils\Html;

class DateTimePickerControl extends TextBase {

    public function getControl() {

        $super = Html::el();
        $control = Html::el('div')->class('controls');
        $xinput = Html::el('div')->class('xdisplay_inputx form-group has-feedback');
        $input = parent::getControl();
        $input->class('form-control datepicker datetimepicker-' . $this->getHtmlName());
        $span = Html::el('span')->class('fa fa-calendar-o form-control-feedback left');
        $xinput->add($input);
        $xinput->add($span);
        $control->add($xinput);
        $super->add($control);
        return $super;
    }

}
