<?php

 /*
  * Pharo
  */

 namespace App\CommonModule\Presenters;

 /**
  * Description of SignPresenter
  *
  * @author hippo
  */
 class SignPresenter extends CommonPresenter {

     public function renderDefault() {
         $this->redirect('in');
     }

     public function renderIn() {
         
     }

     protected function createComponentSignInForm() {
         $form = new \Nette\Application\UI\Form();
         $form->elementPrototype->addAttributes(['class' => 'sky-form boxed']);
         $form->addText('user_login', 'E-mail / login)')
                 ->setAttribute('placeholder', 'User name or Email')
                 ->setRequired('Uveďte svůj registrovaný login.');

         $form->addPassword('user_pass', 'Heslo')
                 ->setAttribute('placeholder', 'Password')
                 ->setRequired('Zadejte heslo.');

         $remember = new \Nette\Forms\Controls\PharoCheckbox('Pamatuj si mne');
         $form->addComponent($remember, 'remember');
         $form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
         $form->addSubmit('send', 'Přihlásit se');

         // call method signInFormSucceeded() on success
         $form->onSuccess[] = $this->signInFormSucceeded;

         // $this->bootstrapize($form);

         return $form;
     }

     public function signInFormSucceeded($form) {
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
             $form->addError($e->getMessage());
         }
     }

     public function renderOut() {
         if (!$this->getUser()->isLoggedIn()) {
             $this->redirect(':Common:Sign:in');
         }
         $this->user->logout(1);
         $this->flashMessage('You have been logged out', 'info');
         $this->redirect(':Common:Sign:in');
     }

 }
 