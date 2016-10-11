<?php

namespace Nette\Forms\Rendering;

use Nette;

/**
 * Description of PharoFormRenderer
 *
 * @author hippo
 */
class PharoFormRenderer extends DefaultFormRenderer {
    //put your code here

    /**
     * Provides complete form rendering.
     * @param  Nette\Forms\Form
     * @param  string 'begin', 'errors', 'ownerrors', 'body', 'end' or empty to render all
     * @return string
     */
    public function render(Nette\Forms\Form $form, $mode = NULL) {
        if ($this->form !== $form) {
            $this->form = $form;
        }

        $s = '<div class="box box-info">';
        if (!$mode || $mode === 'begin') {
            $s .= $this->renderBegin();
        }


        if (!$mode || strtolower($mode) === 'ownerrors') {
            $s .= $this->renderErrors();
        } elseif ($mode === 'errors') {
            $s .= $this->renderErrors(NULL, FALSE);
        }
        $s .= '<div class="box-body">';
        if (!$mode || $mode === 'body') {
            $s .= $this->renderBody();
        }
        $s .= '</div>';
        if (!$mode || $mode === 'end') {
            $s .= $this->renderEnd();
        }
        $s .= '</div>';
        //_print_r( $s );die();
        return $s;
    }

}
