<?php

 namespace App\ProfileModule\Presenters;

 use \Nette\Application\UI\Form;

 class ProfilePresenter extends BasePresenter {

     protected $model;
     protected $data;
     protected $userManager;
     protected $userData;

     /**
      *
      * @var string
      */
     const NOT_UPLOADED = 'Obrázek nebyl nahrán. Pokuste se prosím o znovu nahrání.';

     /**
      *
      * @var string
      */
     const BIGGER_THAN_LIMIT = 'Obrázek nebyl nahrán. Je příliš veliký. Limit je 2MB. Pokuste se prosím o znovu nahrání.';

     /**
      *
      * @var string
      */
     const BAD_IMAGE_SIZE_SMALL = 'Obrázek nebyl nahrán. Je příliš malý. Nahrajte prosím obrázek vetší než 100px. Pokuste se prosím o znovu nahrání.';

     /**
      *
      * @var string
      */
     const BAD_IMAGE_SIZE_BIG = 'Obrázek nebyl nahrán. Je příliš veliký. Maximální velikost je 1500x1500px. Pokuste se prosím o znovu nahrání.';

     /**
      *
      * @var int
      */
     private $sizeLimit = 2097152;
     private $user_level = 1;

     /**
      *
      * @var array
      */
     private $allowed = array(
         'image/jpeg',
         'image/png'
     );

     /**
      *
      * @var int
      */
     private $minSize = 200;
     private $sizex = null;
     private $sizey = null;
     private $ext = null;
     private $md5;

     /**
      *
      * @var array
      */
     private $extAll = array(
         2 => 'jpg',
         3 => 'png'
     );
     private $maxSize = 2500;

     public function startup() {
         parent::startup();
         if ($this->user->isLoggedIn() === false) {
             $this->flashMessage('You must log in to see this section', 'danger');
             $this->redirect(':Common:Sign:in');
         }
         $data = $this->user->getIdentity();
         $this->data = $data;
         $this->userManager = new \App\Model\UserManager($this->db, $data->id);
         $this->userData = $this->userManager->getData();
         \Pharo\UserFileStorage::checkUserStorage($data->id);
     }

     public function beforeRender() {
         parent::beforeRender();
         $this->template->user = $this->data;
         $this->template->user_data = $this->userData;
     }

     public function renderDefault() {
         
     }

     public function renderAvatar() {
         
     }

     public function renderPassword() {
         
     }

     public function renderAddress() {
         
     }

     public function createComponentPersonalForm() {
         $form = new Form();
         $form->addText('first_name', 'First Name')->setAttribute('placeholder', 'First Name');
         $form->addText('last_name', 'Last Name')->setAttribute('placeholder', 'Last Name');
         $mobile = new \Nette\Forms\Controls\PharoMobilePhone('Mobile phone');
         $form->addComponent($mobile, 'mobile');
         $form->addText('interest', 'Interests')->setAttribute('placeholder', 'Development, etc....');
         $form->addTextArea('signature', 'Forum signature', null, 3)->setAttribute('placeholder', "signature");
         $form->addTextArea('about', 'About me', null, 3)->setAttribute('placeholder', "About me");
         $form->addText('website', 'Website URL')->setAttribute('placeholder', 'http://www.website.com');
         $form->addSubmit('save', 'Save Changes')->setAttribute('class', 'btn btn-primary');
         $form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
         $form->onSuccess[] = $this->savePersonalForm;
         $form->setDefaults($this->userData);
         return $form;
     }

     public function savePersonalForm(Form $form) {
         $data = $form->getValues();
         if (empty($data->mobile) === false) {
             $data->mobile = str_replace(['(', ')', '-'], ['', '', ''], $data->mobile);
             $data->mobile = preg_replace('/\s+/', '', $data->mobile);
         }
         $this->db->table('users_data')->where('id_user', $this->data->id)->update($data);
         $this->flashMessage('Personal info updated successfully', 'info');
         $this->redirect('default');
         $this->terminate();
     }

     public function createComponentAvatarForm() {
         $form = new Form();
         $upload = new \Nette\Forms\Controls\PharoSimpleUpload('Avatar');
         $form->addComponent($upload, 'avatar');
         $form->addSubmit('save', 'Upload Avatar')->setAttribute('class', 'btn btn-primary');
         $form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
         $form->onSuccess[] = $this->saveAvatar;
         return $form;
     }

     public function saveAvatar(Form $form) {
         $data = $form->getValues();
         $image = $data->avatar;
         if ($image->isOK() && $this->isCorrectImg($image->getContentType())) {
             if ($image->getSize() > $this->sizeLimit) {
                 $form['avatar']->addError(self::BIGGER_THAN_LIMIT);
             }
             // _print_r ( $form['image']);
             $size = $image->getImageSize();
             if ($size[0] < $this->minSize || $size[1] < $this->minSize) {
                 $form['avatar']->addError(self::BAD_IMAGE_SIZE_SMALL);
             } // vyhod male obrazky
             if ($size[0] > $this->maxSize || $size[1] > $this->maxSize) {
                 $form['avatar']->addError(self::BAD_IMAGE_SIZE_BIG);
             } // vyhod velke obrazky
             $this->sizex = $size[0];
             $this->sizey = $size[1];
             $this->ext = $this->extAll[$size[2]];
         } else {
             $form['avatar']->addError(self::NOT_UPLOADED);
         }
         if ($form->hasErrors() === true) {
             return $form;
         }

         $image_path = \Pharo\UserFileStorage::getAvatarPath($this->data->id);

         $x = 800;
         $y = 600;
         $name = $image_path . 'avatar_big.' . $this->ext;
         $processor = new \Pharo\ImageResize();
         $processor->loadFromFile($image->getTemporaryFile());
         $processor->setOutputDimensions($x, $y);
         $processor->saveImage($name);
         $x = 300;
         $y = 600;
         $name = $image_path . 'avatar.' . $this->ext;
         $processor->loadFromFile($image->getTemporaryFile());
         $processor->setOutputDimensions($x, $y);
         $processor->saveImage($name);
         $x = 150;
         $y = 300;
         $name = $image_path . 'avatar_thumb.' . $this->ext;
         $processor->loadFromFile($image->getTemporaryFile());
         $processor->setOutputDimensions($x, $y);
         $processor->saveImage($name);
         $update_url = \Pharo\UserFileStorage::getAvatarUrl($this->data->id) . 'avatar.' . $this->ext;
         $update = ['avatar' => $update_url];
         $this->db->table('users_data')->where('id_user', $this->data->id)->update($update);
         $this->flashMessage('Avatar has been uploaded','info');
         $this->redirect('avatar'); 
         $this->terminate();
     }

     public function createComponentChangePasswordForm() {
         $form = new Form;
         $form->addPassword('old_password', 'Původní heslo');

         $form->addPassword('new_password', 'Nové heslo')
                 ->addRule(Form::LENGTH, 'minimální délka hesla je 8 znaků (maximálně však 20 znaků)', array(8, 20))
                 ->addRule(Form::PATTERN, 'heslo musí obsahovat alespoň jedno číslo a jedno velké
                písmeno. Pokud neobsahuje číslo nebo velké písmeno, musí obsahovat jiný znak.', \Common\Regexes::PASSWORD);

         $form->addPassword('new_password_repeat', 'Kontrola hesla')
                 ->addRule(Form::EQUAL, 'Hesla se musí shodovat', $form['new_password']);

         $form->addSubmit('submit', 'Nastavit nové heslo');

         //$form->onSuccess[] = $this->saveForm;
         $form->setRenderer(new \Tomaj\Form\Renderer\BootstrapVerticalRenderer);
         return $form;
     }

     /**
      *
      * @param string $type            
      * @return boolean
      */
     private function isCorrectImg($type) {
         return in_array($type, $this->allowed, TRUE);
     }

 }
 