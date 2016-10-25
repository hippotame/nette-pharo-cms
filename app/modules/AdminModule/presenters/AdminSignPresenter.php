<?php

 /*
  * Pharo
  */

 namespace App\AdminModule\Presenters;

 /**
  * Description of AdminSign
  *
  * @author hippo
  */
 use Nette\Application\UI\Form;

 class AdminSignPresenter extends BasePresenter {

     protected $signin = false;

     protected function createComponentSignIn() {
         $form = new Form();
         $form->getElementPrototype()->addAttributes(['class' => 'sky-form boxed']);
         $form->addText('user_login', '')->setRequired();
         $form->addPassword('user_pass')->setRequired();
         $form->addCheckbox('remember')->setAttribute('class', 'checkbox-inline');
         $form->onValidate[] = $this->signInValidate;
         return $form;
     }

     public function signInValidate(Form $form) {
         $values = $form->getValues();

         if ($values->remember) {
             $this->getUser()->setExpiration('7 days', FALSE);
         } else {
             $this->getUser()->setExpiration('20 minutes', TRUE);
         }

         try {
             $this->getUser()->login($values->user_login, $values->user_pass);
             $this->flashMessage('You have been logged in', 'info');
             $this->redirect(':Front:Homepage:default');
         } catch (Nette\Security\AuthenticationException $e) {
             $this->flashMessage($e->getMessage(),'danger');
             $this->redirect(':Admin:AdminSign:default');
         }
     }

 }
 