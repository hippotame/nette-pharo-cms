<?php

 /*
  * Pharo
  */

 namespace App\CommonModule\Presenters;

 use Nette;

 /**
  * Description of SignPresenter
  *
  * @author hippo
  */
 class SignPresenter extends CommonPresenter {

     protected $retURL = '';

     public function renderDefault() {
         $this->redirect('in');
     }

     public function renderIn($module = null) {
         $this->retURL = $module;
     }

     protected function createComponentSignInForm() {
         $form = new \Nette\Application\UI\Form();
         $form->setTranslator($this->translator);
         $form->elementPrototype->addAttributes(['class' => 'sky-form boxed']);
         $form->addText('user_login', 'E-mail / login)')
                 ->setAttribute('placeholder', 'User name or Email')
                 ->setRequired('Use your registered user name or login.');

         $form->addPassword('user_pass', 'Password')
                 ->setAttribute('placeholder', 'Password')
                 ->setRequired('Fill up your password');

         $remember = new \Nette\Forms\Controls\PharoCheckbox('Remeber me');
         $form->addComponent($remember, 'remember');
         $form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
         $form->addSubmit('send', 'OK LOG IN');

         // call method signInFormSucceeded() on success
         $form->onSuccess[] = $this->signInFormSucceeded;

         // $this->bootstrapize($form);

         return $form;
     }

     public function signInFormSucceeded($form) {
         $values = $form->getValues();
         $form->setTranslator($this->translator);
         if ($values->remember) {
             $this->getUser()->setExpiration('7 days', FALSE);
         } else {
             $this->getUser()->setExpiration('20 minutes', TRUE);
         }

         try {
             $this->getUser()->login($values->user_login, $values->user_pass);
             $this->flashMessage('You have been logged in', 'info');
             if (is_null($this->getParameter('module')) === false || empty($this->getParameter('module')) === false) {
                 $this->redirect(':' . $this->getParameter('module') . ':' . $this->getParameter('module') . ':default');
             }
             $this->redirect(':Front:Homepage:default');
         } catch (Nette\Security\AuthenticationException $e) {
             $this->flashMessage($this->translator->translate($e->getMessage(), 1), 'info');
             $form->addError($e->getMessage());
         }
     }

     public function renderOut() {
         if (!$this->getUser()->isLoggedIn()) {
             $this->redirect(':Common:Sign:in');
         }
         $this->user->logout(1);
         $this->flashMessage($this->translator->translate('You have been logged out'), 'info');
         $this->redirect(':Common:Sign:in');
     }

 }
 