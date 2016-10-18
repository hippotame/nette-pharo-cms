<?php

 use Nette;

 namespace App\AdminModule;

 use Nette\Security as NS;

 class Authentificator extends Nette\Object implements NS\IAuthenticator {

     public $db;

     function __construct(Nette\Database\Context $database) {
         $this->db = $database;
     }
     
      function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $row = $this->db->table('users')
            ->where('username', $username)->fetch();

        if (!$row) {
            throw new NS\AuthenticationException('User not found.');
        }

        if (!NS\Passwords::verify($password, $row->password)) {
            throw new NS\AuthenticationException('Invalid password.');
        }

        return new NS\Identity($row->id, $row->role, ['username' => $row->username]);
    }

 }
 