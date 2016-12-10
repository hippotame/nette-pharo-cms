<?php

 /*
  * Pharo
  */

 namespace App\Common\model;

 use Nette;

 /**
  * Description of Authenticator
  *
  * @author hippo
  */
 class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator {

     const
             TABLE_NAME = 'users',
             TABLE_RIGHTS = 'user_rights',
             COLUMN_ID_RIGHTS = 'id_user',
             COLUMN_ID = 'id',
             COLUMN_NAME = 'user_login',
             COLUMN_EMAIL = 'user_email',
             COLUMN_PASSWORD_HASH = 'user_pass',
             COLUMN_USER_STATUS = 'user_status',
             COLUMN_ROLE = 'role';

     /** @var Nette\Database\Context */
     private $database;

     public function __construct(Nette\Database\Context $database) {
         $this->database = $database;
     }

     /**
      * Performs an authentication.
      * @return Nette\Security\Identity
      * @throws Nette\Security\AuthenticationException
      */
     public function authenticate(array $credentials) {
         list($username, $password) = $credentials;
         if (filter_var($username, FILTER_VALIDATE_EMAIL) === false) {
             $column = self::COLUMN_NAME;
         } else {
             $column = self::COLUMN_EMAIL;
         }


         $row = $this->database->table(self::TABLE_NAME)->where($column, $username)->fetch();

         if (!$row) {
             throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
         } else {
             $arr = $row->toArray();
             $password = \App\Model\Security\Password::hash($arr[self::COLUMN_NAME], $password);
             if (\App\Model\Security\Password::verify($password, $arr[self::COLUMN_PASSWORD_HASH])) {

                 if ($arr[self::COLUMN_USER_STATUS] < 1) {
                     throw new Nette\Security\AuthenticationException('Your account has not been activated yet', self::NOT_APPROVED);
                 }

                 unset($arr[self::COLUMN_PASSWORD_HASH]);
                 $rightsObj = new \App\Model\UserManager($this->database, $arr[self::COLUMN_ID]);
                 $rights = $rightsObj->setRights();
                 $arr = $arr + $rights;
                 $role = 'user';
                 return new Nette\Security\Identity($row[self::COLUMN_ID], $role, $arr);
             } else {
                 throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
             }
         }


         unset($arr[self::COLUMN_PASSWORD_HASH]);
         return new Nette\Security\Identity($row[self::COLUMN_ID], 'user', $arr);
     }

 }
 