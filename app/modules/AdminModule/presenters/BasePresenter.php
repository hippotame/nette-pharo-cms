<?php

 namespace App\AdminModule\Presenters;

 use Nette;
 use App\CommonModule\Presenters\CommonPresenter;
 use Nette\Utils\Html;
 use DataTable\DataTable;

 class BasePresenter extends CommonPresenter {

     protected $id = null;
     protected $model;
     public $lang = 1;
     protected $heads = [];
     protected $datas = [];

     public function __construct(Nette\Database\Context $database) {
         parent::__construct($database);
         $this->db = $database;
     }

     protected function startup() {
         parent::startup();
     }

     /*      * ***********************************************************
      * SECURE PART
      * Methods to authentificate ADMINS 
      * ******************************************************************
      */

     final public function isAuth() {
         
     }





     /*      * ***********************************************************
      * EOF SECURE PART
      * Methods to authentificate ADMINS 
      * ******************************************************************
      */

     public function renderDefault() {
         
     }

     public function actionEdit($id = null) {
         die('impelement me');
     }

     public function actionSave() {
         die('impelement me');
     }

     public function actionDelete($id) {
         die('impelement me');
     }

     public function actionUnDelete($id) {
         die('impelement me');
     }

     protected function getRoute() {
         $parameters = $this->getRequest()->getParameters();
         return $this->getRequest()->getPresenterName() . ':' . $parameters['action'];
     }

     protected function getPrm($paramName, $emptyOn = false) {
         if (isset($_GET[$paramName]) && !empty($_GET[$paramName])) {
             return $_GET[$paramName];
         }

         if (isset($_POST[$paramName]) && !empty($_POST[$paramName])) {
             return $_POST[$paramName];
         }

         if (isset($_GET[$paramName]) && $emptyOn) {
             return $_GET[$paramName];
         }

         if (isset($_POST[$paramName]) && $emptyOn) {
             return $_POST[$paramName];
         }

         return false;
     }

     public function makeLink($view) {
         return $this->presenter->link(str_replace('Admin:', '', $this->presenter->getName()) . ':' . $view . '');
     }

     protected function bootstrapize_controls(&$container) {
         foreach ($container->getComponents() as $control) {
             if ($control instanceof Nette\Application\UI\Form) {
                 $this->bootstrapize_controls($control);
                 continue;
             }
             if ($control instanceof Nette\Forms\Controls\Button) {
                 $control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-default');
                 $usedPrimary = TRUE;
             } elseif (
                     $control instanceof Nette\Forms\Controls\TextInput || 
                     $control instanceof Nette\Forms\Controls\SelectBox || 
                     $control instanceof Nette\Forms\Controls\MultiSelectBox) {
                 $control->getControlPrototype()->addClass('form-control');
             } elseif (
                     $control instanceof Nette\Forms\Controls\Checkbox || 
                     $control instanceof Nette\Forms\Controls\CheckboxList || 
                     $control instanceof Nette\Forms\Controls\RadioList) {
                 $control->getSeparatorPrototype()
                         ->setName('');
                         //->addClass('form-control');
                 //$control->getControlPrototype()->type
             }
             if ($control instanceof Nette\Forms\Controls\Checkbox) {
                 $control->getLabelPrototype()->addClass('checkbox');
             } elseif (
                     $control instanceof Nette\Forms\Controls\CheckboxList || 
                     $control instanceof Nette\Forms\Controls\RadioList) {
                 
                 $control->getLabelPrototype()->addClass('checkbox');
             }
         }
     }

     protected function bootstrapize(Nette\Application\UI\Form &$form, $orientation = 'form-horizontal') {

         $form->setRenderer(new Nette\Forms\Rendering\PharoFormRenderer);
         $renderer = $form->getRenderer();
         $renderer->wrappers['controls']['container'] = NULL;
         $renderer->wrappers['pair']['container'] = 'div class=form-group';
         $renderer->wrappers['pair']['.error'] = 'has-error';
         $renderer->wrappers['control']['container'] = 'div class=col-lg-10';
         $renderer->wrappers['label']['container'] = 'div class="col-lg-2 col-sm-2 control-label"';
         $renderer->wrappers['control']['description'] = 'p class=help-block';
         $renderer->wrappers['control']['errorcontainer'] = 'p class=help-block';

         $form->getElementPrototype()->class($orientation);
         $form->getElementPrototype()->addAttributes(['role' => "form"]);
         $this->bootstrapize_controls($form);
     }

     /**
      * 
      * @param string $time
      * @return \Nette\Utils\DateTime
      */
     public function fromEditor($time) {
         $datetime = \Nette\Utils\DateTime::createFromFormat('d/m/Y', $time);
         return $datetime->format('Y-m-d H:i:s');
     }

     /**
      * 
      * @param string $time
      * @return \Nette\Utils\DateTime
      */
     public function toEditor($time) {
         $datetime = \Nette\Utils\DateTime::createFromFormat('Y-m-d H:i:s', $time);
         return $datetime->format('d/m/Y');
     }

     /**
      * 
      * @param string $string
      * @return string
      */
     public function cutPerex($string) {
         $string = strip_tags($string);
         return \Nette\Utils\PharoString::truncate($string, 100, '...');
     }

     /*
      * (non-PHPdoc)
      * @see \Nette\ComponentModel\Container::createComponent()
      */

     protected function transColumn(Nette\Application\UI\Form &$form) {
         //dump( $this->data );
         $defaults = [];
         forEach ($this->data as $key => $row) {
             $defaults[$key] = $row;
         }
         forEach ($this->data as $key => $row) {
             if (preg_match('/\_txt/i', $key)) {
                 $id_name = str_replace('_txt', '', $key);
                 $form->addHidden($id_name, $this->data->$key);
                 $defaults[$key] = $this->data->$id_name;
                 $defaults[$id_name] = $this->data->$key;
             }
             if (preg_match('/date\_/i', $key)) {
                 if ($key == 'date_created') {
                     continue;
                 }
                 if ($row instanceof Nette\Utils\DateTime) {
                     $defaults[$key] = $row->format('d/m/Y');
                 }
             }
         }
         return $defaults;
     }
     
     
     public function transCheckbox($value) {
         if( $value == 'on' ) {
             return 1;
         } 
         return 0;
     }

     /*
      * (non-PHPdoc)
      * @see \Nette\ComponentModel\Container::createComponent()
      */

     protected function createComponentDataTable() {
         $object = new DataTable();
         $object->addHeads($this->heads);
         $object->setDataSource($this->datas);
         return $object;
     }

 }
 