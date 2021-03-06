<?php

 namespace App\CommonModule\Presenters;

 use Nette;
 use App\Model\Translate;

 class CommonPresenter extends Nette\Application\UI\Presenter {

     protected $db;
     public $useAnalytics = false;
     private $templateName = 'pharocom';
     protected $userManager;
     protected $userData;
     protected $id;

     

     /**
      *
      * @var array
      */
     protected $data;

     /**
      *  where show slideshow
      *
      * @var array
      */
     protected $modulesWithSlideshow = ['Forum', 'Profile', 'Common'];

     /** @var \App\Model\Translate */
     public $translator;
     public $languages_list = [1];
     public $languages_names = ['CZ'];

     public function __construct(Nette\Database\Context $database) {
         $this->db = $database;
     }

     protected function startup() {
         parent::startup();
         $this->setTemplateName();
         $this->languages_list = $this->context->parameters['app']['languages_list'];
         $this->languages_names = $this->context->parameters['app']['languages_names'];
         $this->translator = new Translate($this->db, $this->session, $this->getModule());
     }

     protected function beforeRender() {
         parent::beforeRender();
         $this->template->setTranslator($this->translator);
         $this->template->module = $this->getModule();
         $this->template->action = $this->getAction();
         $this->template->modulesWithSlideshow = $this->modulesWithSlideshow;
        
     }
     
     public function isBasicAuth() {
         return $this->getUser()->isLoggedIn();
     }

     public function getUser() {
         $user = parent::getUser();
         $user->getStorage()->setNamespace('customeruser');
         return $user->setAuthenticator(new \App\Common\model\Authenticator($this->db));
     }

     public function restrictAccess($rights = 100) {
         $module = $this->getModule();
         if ($this->user->isLoggedIn() === false) {
             $this->flashMessage($this->translator->translate(sprintf('You must sign in to see %s section', $module), 1), 'danger');
             $this->redirect(':Common:Sign:in', $module);
         }

         if (isset($this->user->getIdentity()->$module) === true) {
             if ($this->user->getIdentity()->$module >= $rights) {
                 $this->data = $this->user->getIdentity();
                 $this->userManager = new \App\Model\UserManager($this->db, $this->data->id);
                 $this->userData = $this->userManager->getData();
                 \Pharo\UserFileStorage::checkUserStorage($this->data->id);
                 $this->template->user = $this->data;
                 $this->template->user_data = $this->userData;
                 return true;
             }
         }
         $this->template->setFile(__TPL__ . '/pharocom/Common/components/restricted.latte');
         $this->template->render();
         $this->terminate();
     }

     public function formatLayoutTemplateFiles() {
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

     public function getModule() {
         if (!$a = strrpos($this->name, ':')) { // not in module
             return false;
         }
         return substr($this->name, 0, $a);
     }

     public function getAppParameter($parameter) {
         // @TODO check if param exists 
         return $this->context->getParameters()['app'][$parameter];
     }

     /**
      * 
      * @param bool $camel
      * @return string
      */
     public function getPureName($camel = true) {
         $pos = strrpos($this->name, ':');
         if (is_int($pos)) {
             $name = substr($this->name, $pos + 1);
             if ($camel === false) {
                 return Nette\Utils\PharoString::action2path($name);
             }
             return $name;
         }

         return $this->name;
     }

     public function getActive($name) {
         if (preg_match('/' . $name . '/i', $this->getPureName(false))) {
             return true;
         }
         return false;
     }

     /**
      * Formats view template file names.
      *
      * @return array
      */
     public function formatTemplateFiles() {
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

     private function setTemplateName() {
         $this->template->useAnalytics = $this->useAnalytics;
         // using the templateName from neon config name
         $templateName = $this->context->getParameters()['templateName'];
         $this->templateName = $templateName;
         $this->template->baseThemePath = '/themes/_data/';

         // getting the template name from the default database in table templates
         // $context = $this->context->getService('database.default.context');
         // $selection = $context->table('templates')->where('default = 1');
         // TODO check if query has a result
         // TODO check if the directory exsits
         // $this->templateName = $selection[0]->name;
     }

     public function createComponentUserBox() {
         $usrbox = new \App\CommonModule\Components\UsrboxControl();
         $usrbox->setDb($this->db);
         $usrbox->setUser($this->user);
         $usrbox->setTranslator($this->translator);
         $usrbox->setUsersObj(new \App\DB\UserModel($this->db));
         //dump( $usrbox ); die();
         return $usrbox;
     }

     public function createComponentBreadcrumbs() {
         return new \App\CommonModule\Components\BreadcrumbsControl();
     }
     
     
     public function getCurrentHost() {
         $httpRequest = $this->context->getByType('Nette\Http\Request');
         $uri = $httpRequest->getUrl();
         return sprintf('%s://%s/',$uri->scheme,$uri->host);
     }

 }
 