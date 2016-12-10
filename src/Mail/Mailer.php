<?php

 /*
  * Pharo
  */

 namespace Pharo\Mail;

 /**
  * Description of Mailer
  *
  * @author hippo
  */
 use Nette;
 use Nette\Mail\SendmailMailer;
 use Pharo\Mail\MailerException;

 class Mailer extends \Nette\Mail\Message {

     /** @var array */
     public static $defaultHeaders = array(
         'MIME-Version' => '1.0',
         'X-Mailer'     => 'Nette Pharo CMS',
     );

     const LAYOUT_TPL_DIR = '../layouts/';
     const EXT = '.latte';

     /**
      *
      * @var Nette\Application\UI\Presenter
      */
     protected $_presenter = null;

     /**
      * holder for object
      * 
      * @var Nette\Mail\SendMailer
      */
     public $mail;

     /**
      * Holder for params
      * 
      * @var array params
      */
     protected $params = [];
     protected $unset_lay = '';
     protected $from;

     public function __construct() {
         parent::__construct();
     }

     /**
      * Sets layout for emailing 
      * @param string 
      */
     public function setBodyType($type) {
         if (file_exists(__MAIL_TPL__ . $type . self::EXT) === true) {
             $latte = new \Latte\Engine();
             $this->setHtmlBody($latte->renderToString(__MAIL_TPL__ . $type . self::EXT, $this->params));
         } else {
             $this->unset_lay = __MAIL_TPL__ . $type . self::EXT;
         }
     }

     public function addParam($name, $value) {
         $this->params[$name] = $value;
     }

     /**
      * Final function will just send the message
      */
     final public function send() {
         $this->validateDefaults();
         $this->getDefaultsFrom();
         $mailer = new SendmailMailer;
         $mailer->send($this);
     }

     /**
      * Default from
      */
     public function getDefaultsFrom() {
         $this->setFrom($this->_presenter->getAppParameter('mailer_def_from'));
     }

     /**
      * Checkers 
      */
     private function validateDefaults() {
         if (is_null($this->_presenter) === true) {
             throw new MailerException('Mailer Presenter must be set');
         }
         if (empty($this->body) === true) {
             throw new MailerException('BODY must be set');
         }
     }

     /**
      * SETTERS & GETTERS 
      */
     public function getPresenter() {
         return $this->_presenter;
     }

     public function setPresenter(Nette\Application\UI\Presenter $presenter) {
         $this->_presenter = $presenter;
         return $this;
     }

 }
 