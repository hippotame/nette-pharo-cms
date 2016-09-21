<?php
namespace App\CommonModule\Presenters;

use Nette;
;

class CommonPresenter extends Nette\Application\UI\Presenter
{

    /**
     * @inject @var \Kdyby\Doctrine\EntityManager
     */
    public $em;

    private $templateName = 'pharocom';

    public function formatLayoutTemplateFiles()
    {
        $this->setTemplateName();
        $name = $this->getName();
        $module = $this->getModule();
        $presenter = substr($name, strrpos(':' . $name, ':'));
        $layout = $this->layout ? $this->layout : 'layout';
        $list = array(
            __TPL__ . $this->templateName . '/' . $module . "/$presenter/@$layout.latte",
            __TPL__ . $this->templateName . '/' . $module . "/$presenter/@$layout.latte"
        );
        do {
            $list[] = __TPL__ . $this->templateName . '/' . $module . "/@$layout.latte";
            $dir = dirname(__TPL__);
        } while ($dir && ($name = substr($name, 0, strrpos($name, ':'))));
        return $list;
    }

    public function getModule()
    {
        if (! $a = strrpos($this->name, ':')) { // not in module
            return false;
        }
        return substr($this->name, 0, $a);
    }

    /**
     * Formats view template file names.
     *
     * @return array
     */
    public function formatTemplateFiles()
    {
        $module = $this->getModule();
        $this->setTemplateName();
        $name = $this->getName();
        $presenter = substr($name, strrpos(':' . $name, ':'));
        $module = $this->getModule();

        return array(
            __TPL__ . $this->templateName . '/' . $module . "/$presenter/$this->view.latte",
            __TPL__ . $this->templateName . '/' . $module . "/$presenter/$this->view.latte"
        );
    }

    private function setTemplateName()
    {

        // using the templateName from neon config name
        $templateName = $this->context->getParameters()['templateName'];
        $this->templateName = $templateName;

        // getting the template name from the default database in table templates
        // $context = $this->context->getService('database.default.context');
        // $selection = $context->table('templates')->where('default = 1');
        // TODO check if query has a result
        // TODO check if the directory exsits
        // $this->templateName = $selection[0]->name;
    }
}