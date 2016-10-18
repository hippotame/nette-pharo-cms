<?php

 namespace App\AdminModule\Presenters;

 use App\DB\UserModule;
 use Nette\Application\UI\Form;

 class UsersPresenter extends BasePresenter {

     protected $data;
     protected $heads = [
         'id'           => [
             'name'  => '#ID',
             'width' => 20
         ],
         'user_login'   => [
             'name'  => 'User Login',
             'width' => 150,
         ],
         'user_email'   => [
             'name' => 'User email',
         ],
         'display_name' => [
             'name'  => 'Title',
             'width' => 150
         ],
         'adminRights' => [
             'name'  => 'ADMIN RIGTS',
             'width' => 100
         ],
         'edit'         => [
             'name'  => 'EDIT',
             'width' => 100
         ],
         'delete'       => [
             'name'  => 'Delete',
             'width' => 100
         ]
     ];
     protected $datas = [];

     protected function startup() {
         parent::startup();
         $this->model = new UserModule($this->db);
     }

     public function renderDefault() {

         if ($this->model->count() < 1) {
             $this->redirect('edit');
         }
         $this->datas = $this->model->load($this->lang);
     }
     
     
     public function actionAdminRights ($id) {
         
     }

     public function actionEdit($id = null) {
         
     }

     /* id	

       2	user_login
       3	user_pass
       4	user_nicename
       5	user_email
       6	user_url
       7	registereddatetime
       8	user_activation_key
       9	user_status	y
       10	display_name
       11	is_admin	tinyint(1)
      */

     protected function createComponentEdit() {
         $form = new Form();

         $form->addText('user_login', 'User Login')->setRequired(true);
         $form->addPassword('user_pass', 'User Password')->setRequired(true);
         $form->addPassword('passwordVerify', 'User Password Verify')
                 ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                 ->addRule(Form::EQUAL, 'Hesla se neshodují', $form['user_pass']);
         $form->addText('user_email', 'User Email')->setType('email')->setRequired(true);
         $form->addText('user_url', 'User URL');
         $form->addText('display_name', 'Display Name');
         $is_admin = new \Nette\Forms\Controls\PharoCheckbox('Is user admin');
         $form->addComponent($is_admin, 'is_admin');
         if (is_null($this->id) === false) {
             $form->addHidden('id', $this->id);
             $defaults = $this->transColumn($form);
             $form->setDefaults($defaults);
         }
         $date_registered = new \Nette\Database\SqlLiteral('NOW()');
         $form->addHidden('date_registered', $date_registered);
         $form->addSubmit('send', 'Save User'); //->setAttribute('class', 'btn btn-success');

         $form->onValidate[] = $this->editValidate;
         $form->onSuccess[] = $this->editSuccessed;
         $this->bootstrapize($form);
         return $form;
     }

     /**
      * 
      * @param Form $form
      */
     public function editValidate(Form $form) {
         $data = $form->getValues();
         //dump( $data ); die();
         if (empty($data->user_login) === true) {
             $form['user_login']->addError('User Login');
         }
         if (empty($data->user_pass) === true) {
             $form['user_pass']->addError('You must add password');
         }
         /*
          * @TODO add validation 
          */
     }

     /**
      * 
      * @param Form $form
      */
     public function editSuccessed(Form $form) {
         $data = $form->getValues();

         $data['user_pass'] = \App\Model\Security\Password::hash($data['user_login'], $data['user_pass']);
         unset($data['passwordVerify']);
         if ($data['is_admin'] == 'on') {
             $data['is_admin'] = 1;
         } else {
             $data['is_admin'] = 0;
         }


         $this->model->store($data);
         $this->flashMessage('User edited successfully', 'success');
         $this->redirect('Users:');
     }

 }
 