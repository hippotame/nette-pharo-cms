<?php

 /**  * Model, Menu * @author hippo */

 namespace App\Model;

 use Nette;
 use Nette\Database\SqlLiteral;
 use Nette\Utils\Strings;

 class Translate implements \Nette\Localization\ITranslator {

     const NOT_STRING = 'UNKNOWN';
     const COLUMN = 'value';
     const PLU_2_COLUMN = 'plural_2';
     const PLU_5_COLUMN = 'plural_5';

     protected $db;
     protected $table = 'i18n';

     /**
      *
      * @var int default language
      */
     public $default = 1;

     /** @var Nette\Http\Session */
     private $session;

     /** @var Nette\Http\SessionSection */
     private $lang_setting;
     private $lang_settings_name = 'lang.settings';

     /** @var Nette\Http\SessionSection */
     private $dummy;
     private $lang_values;
     private $lang_values_name = 'lang.values';

     /**
      *
      * @var string
      */
     private $module;

     /**
      * 
      * @param \Nette\Database\Context $database
      * @param Nette\Http\Session $session
      */
     public function __construct(\Nette\Database\Context $database, Nette\Http\Session $session, $module) {
         $this->db = $database;
         $this->session = $session;
         $this->lang_setting = $this->session->getSection($this->lang_settings_name);
         $this->lang_values = []; //$this->session->getSection($this->lang_values_name);
         $this->module = $module;

         $this->setLang();
         $this->load();
     }

     public function setLang() {
         $this->lang_setting->lang = $this->default;
     }

     public function translate($message, $common = false, $count = NULL) {

         //dump(func_get_args());
         $str = Strings::toAscii($message);
         $str = Strings::upper($str);
         $str = Strings::webalize($str, null, false);
         $str = str_replace('-', '_', $str);
         $module = $this->module;
         if ($common !== false) {
             $module = 'Common';
         }
         $str = sprintf('%s.%s', $module, $str);
         // dump($str);
         if (array_key_exists($str, $this->lang_values) === false) {
             $data = [
                 'id'     => $str,
                 'module' => $this->module,
                 'status' => new SqlLiteral('NULL')
             ];
             $this->db->table($this->table)->insert($data);
             $this->load();
         } else {
             if (is_null($this->lang_values[$str]['value']) === true) {
                 return $message;
             } else {
                 $column = self::COLUMN;
                 $return = false;
                 if ($count !== null) {
                     if ($count > 1 && $count < 5) {
                         $column = self::PLU_2_COLUMN;
                     }
                     if ($count > 4.999999999999999999) {
                         $column = self::PLU_5_COLUMN;
                     }
                     if (preg_match('/%s/', $this->lang_values[$str][$column]) < 1 ) {
                         $column = self::COLUMN;
                     } else {
                         return sprintf($this->lang_values[$str][$column], $count);
                     }
                 }

                 return $this->lang_values[$str][$column];
             }
         }
         return $message;
     }

     public function load() {
         $res = $this->db->table($this->table)->where('lang', $this->lang_setting->lang)->fetchAll();
         $data = array();
         forEach ($res as $key => $val) {
             $data[$val->id] = $val->toArray();
         }
         $this->lang_values = $data;
     }

 }
 