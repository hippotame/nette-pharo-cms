<?php

 namespace Pharo;

/*
  * Pharo
  */

 /**
  * Description of UserFileStorage
  *
  * @author hippo
  */
 class UserFileStorage {
     /**
      * 
      * @param int $user
      * 
      * /www/storage/%user/avatar/
      * /www/storage/%user/attachment
      * /www/storage/%user/cache
      * 
      */

     /**
      * @var Singleton The reference to *Singleton* instance of this class
      */
     private static $instance;
     private static $sandbox = [
         '/%s/users/%s/avatar',
         '/%s/users/%s/attachment',
         '/%s/users/%s/cache',
     ];
     private static $userdir = '/%s/users/%s/%s/';
     private static $userurl = '%s/%s/avatar/';
     
     private static $crypt = '_c_%s_c_';

     /**
      * Returns the *Singleton* instance of this class.
      *
      * @return Singleton The *Singleton* instance.
      */
     public static function getI() {
         if (null === static::$instance) {
             static::$instance = new static();
         }

         return static::$instance;
     }

     /**
      * Protected constructor to prevent creating a new instance of the
      * *Singleton* via the `new` operator from outside of this class.
      */
     protected function __construct() {
         
     }

     static public function checkUserStorage($user) {
         if (is_dir(__STORAGE__ . '/' . $user) === false) {
             \Nette\Utils\FileSystem::createDir(__STORAGE__ . '/users/' . self::User($user));
         }
         forEach (self::$sandbox as $dir) {
             $checkDir = sprintf($dir, __STORAGE__, self::User($user));
             if (is_dir($checkDir) === false) {
                 \Nette\Utils\FileSystem::createDir($checkDir);
             }
         }
     }

     static public function getUserDir($user, $type) {
         self::checkUserStorage($user);
         return sprintf(self::$userdir, __STORAGE__, self::User($user), $type);
     }

     static public function getAvatarPath($user) {
         return self::getUserDir($user, 'avatar');
     }

     static public function getAvatarUrl($user) {
         return sprintf(self::$userurl, __STORAGE_URL, self::User($user));
     }

     static public function getAvatar($user) {
         
     }

     static public function User($user) {
         return XorCrypt::encrypt(sprintf(self::$crypt,$user));
     }

 }
 