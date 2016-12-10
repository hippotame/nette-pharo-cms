<?php

 /*
  * Pharo
  */

 namespace App\CommonModule\Presenters;

 /**
  * Description of RegisterPresenter
  *
  * @author hippo
  */
 use \Nette\Application\UI\Form;

 class RegisterPresenter extends CommonPresenter {

     public function renderDefault() {
         //dump($this->getAppParameter('mailer_def_from') );die();
     }

     public function renderRegistered() {
         
     }

     public function actionActivate($id) {
         if (is_null($id)) {
             $this->redirect('Sign:in');
         }
         $query = $this->db->table('users')->select('id')->where('user_activation_key', $id);
         $result = $query->fetch();
         if ($result === false) {
             $this->flashMessage($this->translator->translate('Activation link is valid for 24 hour. Please register again or sign in'),'info');
             $this->redirect('Register:default');
         } else {
             $data = [
                 'date_registered'     => new \Nette\Database\SqlLiteral('NOW()'), 
                 'user_status' => 1
             ];
             $query->update($data);
             forEach (\App\Common\model\Acl::DEF_REGISTERED_RIGHTS as $module => $rights) {
                 $this->db->table('users_rights')->insert( [
                     'id_user'     => $result->id,
                     'module'      => $module,
                     'pharo_read'  => $rights[0],
                     'pharo_write' => $rights[1],
                     'pharo_admin' => $rights[2]
                 ]);
             }
             $this->flashMessage($this->translator->translate('Your account has been activated. Please log in'),'info');
             $this->redirect('Sign:in');
         }


         dump($result);
         die();
         //$res = 
     }

     protected function createComponentRegisterForm() {


         $form = new Form();
         $form->setTranslator($this->translator);
         $form->elementPrototype->addAttributes(['class' => 'nomargin sky-form boxed']);
         $form->addText('user_login', '')
                 ->setRequired()
                 ->addRule(Form::FILLED, 'Fill up this field')
                 ->setAttribute('placeholder', 'User login');

         $form->addText('user_email', 'E-mail: *')
                 ->setAttribute('placeholder', 'Email address')
                 ->addRule(Form::FILLED, 'Vyplňte Váš email')
                 ->addCondition(Form::FILLED)
                 ->addRule(Form::EMAIL, 'Neplatná emailová adresa');

         $form->addPassword('user_pass', '')
                 ->setAttribute('placeholder', 'User Password')
                 ->setOption('description', 'Alespoň 3 znaků')
                 ->addRule(Form::FILLED, 'Vyplňte Vaše heslo')
                 ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaků.', 3);
         $form->addPassword('user_pass2', '')
                 ->setAttribute('placeholder', 'Confirm password')
                 ->addConditionOn($form['user_pass'], Form::VALID)
                 ->addRule(Form::FILLED, 'Confirm password')
                 ->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['user_pass']);
         $ckBox = new \Nette\Forms\Controls\PharoSimpleCheckbox('');
         $form->addComponent($ckBox, 'agree_to_terms');
         $form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
         $form->addSubmit('send', 'REGISTER');
         $form->onValidate[] = $this->registerFormValidate;
         $form->onSuccess[] = $this->registerFormSucceeded;

         return $form;
     }

     public function registerFormValidate(Form $form) {
         $data = $form->getValues();
         //dump( $form);

         /* if (empty($data->user_login) === true) {
           $form->getComponent('user_login')->addError('User login must not be empty');
           } else {
           if ($this->db->table('users')->where('user_login', $data->user_login)->count() > 0) {
           $form['user_login']->addError('User login allready exists');
           }
           }
           if (empty($data->user_email) === true) {
           $form['user_email']->addError('User email must not be empty');
           } else {
           if (\Nette\Utils\Validators::isEmail($data->user_email) === false) {
           $form['user_email']->addError('User email must be in right format');
           }
           if ($this->db->table('users')->where('user_email', $data->user_email)->count() > 0) {
           $form['user_email']->addError('User email allready exists');
           }
           }
           if (empty($data->user_pass) === true || empty($data->user_pass2) === true) {
           if (empty($data->user_pass) === true) {
           $form['user_pass']->addError('Password must be filled up');
           }
           if (empty($data->user_pass2) === true) {
           $form['user_pass2']->addError('Password must be filled up');
           }
           } else {
           if ($data->user_pass !== $data->user_pass2) {
           $form['user_pass']->addError('Both Passwords must match each other');
           $form['user_pass2']->addError('Both Passwords must match each other');
           }
           } */
         if (empty($data->agree_to_terms) === true) {
             $form->getComponent('agree_to_terms')->addError('You must agree with terms');
         }

         return;
     }

     public function registerFormSucceeded(Form $form) {
         $data = $form->getValues();
         $final = [];
         $final['user_login'] = $data->user_login;
         $final['display_name'] = $data->user_login;
         $final['user_email'] = $data->user_email;
         $final['user_pass'] = \App\Model\Security\Password::hash($data->user_login, $data->user_pass);
         $final['date_registered'] = new \Nette\Database\SqlLiteral('NULL');
         $final['user_activation_key'] = sha1(time() . $data->user_login . '-' . '$%$#@');
         $final['user_status'] = '0';
         $final['is_admin'] = '0';
         $this->db->table('users')->insert($final);

         $mail = new \Pharo\Mail\Mailer();
         $mail->setPresenter($this);
         $mail->addParam('key', $final['user_activation_key']);
         $mail->addParam('user_login', $final['user_login']);
         $mail->addParam('lang', 1);
         $mail->addParam('url_host', $this->getCurrentHost());
         $mail->addTo('hippo@network.cz');
         //$mail->addTo($final['user_email']);
         $mail->setBodyType('register');
         $mail->setBody('test');
         $mail->send();
         unset( $final['user_pass']);
         \App\Datastores\Datastore::gI()->register_data = $final;
         $this->redirect('registered');
     }

     public function actionCheckUserName() {
         echo $this->db->table('users')->where('user_login', $_POST['user_login'])->count();
         die();
     }

     public function actionCheckUserEmail() {
         if (empty($_POST['user_email'])) {
             echo 0;
             die();
         }
         echo $this->db->table('users')->where('user_email', $_POST['user_email'])->count();
         die();
     }

 }
 