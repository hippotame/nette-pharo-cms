<?php

 namespace App\DB;

 use DBExc\DBExeption;

 class AbstractDBModel {

     /**
      *
      * @var type 
      */
     protected $db;
     protected $table;
     private $data;
     public $txt_table;
     protected $useTXT = false;
     protected $limitstart = null;
     protected $limitend = null;

     /**
      *
      * @var array
      */
     protected $txts;

     /**
      *
      * @var array
      */
     protected $cols = [];

     /**
      *
      * @var boolean
      */
     protected $orderings = false;

     /**
      *
      * @param \Nette\Database\Context $database
      */
     public function __construct(\Nette\Database\Context $database, $txtTable = null) {
         $this->db = $database;
         $this->setTXTTable($txtTable);
         $this->txts = new \stdClass();
     }

     /**
      * 
      * @param int $id
      * @param int $lang
      * @return boolean
      */
     public function delete($id, $lang = 1) {
         $data = $this->load($lang, $id);
         forEach ($this->txts as $key => $val) {
             $this->deleteTxt($data->$key);
         }
         $this->getSelection()->where('id', $id)->delete();
         return true;
     }

     /**
      * 
      * @return array \Nette\Database\iStructure
      */
     private function getColumnsStructure() {
         return $this->db->getStructure()->getColumns($this->table);
     }

     protected function getColumns() {

         forEach ($this->getColumnsStructure() as $key => $col) {
             if (preg_match('/\_txt/i', $col['name'])) {
                 $this->txts->$col['name'] = $col;
             }
             $this->cols[$col['name']] = $col;
             if ($col['name'] == 'ordering') {
                 $this->orderings = true;
             }
         }
     }

     /**
      * 
      * @param type $lang
      * @param type $id
      * @return \Nette\Database\Table\ActiveRow
      * @return \Nette\Database\Table\Selection
      */
     public function load($lang = 1, $id = null) {
         $this->getColumns();

         $joins = '';
         $selects = 'a.*';

         $a = 1;
         forEach ($this->txts as $key => $val) {

             $selects .= sprintf(",t%s.%s AS %s", $a, 'value', str_replace('_txt', '', $key));
             $selects .= sprintf(",t%s.date_updated AS t%s_updated", $a, $a);
             $selects .= sprintf(",t%s.id AS t%s_id", $a, $a);

             $joins .= " LEFT JOIN `" . $this->txt_table . "` t" . $a . " ";
             $joins .= " ON ( t" . $a . ".id=a." . $key . " AND t" . $a . ".lang=" . $lang . " )";

             $a++;
         }
         $order = '';
         if ($this->orderings === true) {
             $order .= " ORDER BY ordering ASC";
         }
         $sql = 'SELECT ' . $selects . ' FROM `' . $this->table . '` a ';

         $sql = $sql . $joins;

         if (is_null($id) === false) {
             $sql .= " WHERE a.id='" . $id . "'";
             //echo $sql; die();

             return $this->db->query($sql)->fetch();
         } else {

             $sql = $sql . $order;

             return $this->db->query($sql)->fetchAll();
         }
     }

     

     /**
      * 
      * @param \Nette\Utils\ArrayHash $data
      * @param int $lang
      */
     public function store(\Nette\Utils\ArrayHash $data, $lang = 1) {
         if (isset($data->id) === true) {
             $this->update($data, $lang);
         } else {
             $this->insert($data, $lang);
         }
     }

     /**
      * 
      * @param \Nette\Utils\ArrayHash $data
      * @param int $lang
      * @return int
      * @throws Exception
      */
     protected function insert(\Nette\Utils\ArrayHash $data, $lang) {

         try {
             $txt_updates = new \stdClass();
             forEach ($data as $key => $val) {

                 if (preg_match('/\_txt/', $key)) {
                     $txt_updates->$key = null;
                 }
             }
             //dump($txt_updates); //die();
             forEach ($txt_updates as $key => $val) {
                 $id_name = str_replace('_txt', '', $key);
                 $data->$key = $this->saveTxt($data->$key, $lang, null);
                 unset($data->$id_name);
             }
             $this->setDefaultOrdering($data);
             $id = $this->getSelection()->insert($data);

             return $id;
         } catch (Exception $e) {
             throw new \DBExc\DBException('There is error to update DB' . $e->getMessage());
         }
     }

     /**
      * 
      * @param \Nette\Utils\ArrayHash $data
      * @param int $lang
      * @return int
      * @throws Exception
      */
     protected function update(\Nette\Utils\ArrayHash $data, $lang) {
         try {
             $txt_updates = new \stdClass();
             forEach ($data as $key => $val) {

                 if (preg_match('/\_txt/', $key)) {
                     $txt_updates->$key = $val;
                 }
             }
             forEach ($txt_updates as $key => $val) {
                 $id_name = str_replace('_txt', '', $key);
                 $data->$key = $this->saveTxt($val, $lang, $data->$id_name);
                 unset($data->$id_name);
             }
             $this->getSelection()->where('id', $data->id)->update($data);
             return $data->id;
         } catch (Exception $e) {
             throw new \DBExc\DBException('There is error to update DB' . $e->getMessage());
         }
     }

     /**
      * 
      * @param \Nette\Utils\ArrayHash $data
      */
     protected function setDefaultOrdering(&$data) {
         
     }

     /**
      * 
      * @param type $table
      * @return \Nette\Database\Table
      * @throws \Exception
      */
     public function getSelection($table = null) {
         if (is_null($table) === false) {
             return $this->db->table($table);
         }
         if (is_null($this->table) === false) {
             return $this->db->table($this->table);
         }
         throw new \Exception('Neni zadana tabulka');
     }

     /**
      * 
      * @param string $txt_table
      * @return true
      * @throws DBExeption
      */
     public function setTXTTable($txt_table = null) {

         if (is_null($this->table) === false) {
             $this->txt_table = $this->table . '_txt';
             return true;
         }
         if ($txt_table === false) {
             return true;
         }
         throw new DBExeption('Table TXT se musi nasetovat');
     }

     /**
      * 
      * @param string $value
      * @param int $lang
      * @param int $id
      * @return int
      */
     public function saveTxt($value, $lang, $id = null) {

         if (is_null($id) === true) {
             $id = $this->getSelection($this->txt_table)->where('lang', $lang)->max('id +1');
             if (is_null($id) === true) {
                 $id = 1;
             }
         } else {
             $this->getSelection($this->txt_table)->where('id', $id)->where('lang', $lang)->delete();
         }
         $this->getSelection($this->txt_table)->insert([
             'id'           => $id,
             'lang'         => $lang,
             'value'        => $value,
             'date_updated' => new \Nette\Database\SqlLiteral('NOW()')
         ]);
         return $id;
     }

     /**
      * 
      * @param int $id
      * @param null $lang 
      * @param int $lang
      * @return boolean
      */
     public function deleteTxt($id, $lang = null) {
         if (is_null($lang) === false) {
             $this->getSelection($this->txt_table)->where('id', $id)->delete();
             return true;
         }
         $this->getSelection($this->txt_table)->where('id', $id)->delete();
         return true;
     }

     /**
      * 
      * @return \Nette\Database\Table\Selection
      */
     public function getAll() {
         return $this->getSelection()->fetchAll();
     }

     public function count() {
         return $this->getSelection()->count();
     }

     /**
      *
      * @return string
      */
     public function getUseTXT() {
         return $this->useTXT;
     }

     /**
      *
      * @param string $useTXT
      */
     public function setUseTXT($useTXT) {
         $this->useTXT = $useTXT;
         return $this;
     }

     /**
      *
      * @return the unknown_type
      */
     public function getLimitstart() {
         return $this->limitstart;
     }

     /**
      *
      * @param unknown_type $limitstart
      */
     public function setLimitstart($limitstart) {
         $this->limitstart = $limitstart;
         return $this;
     }

     /**
      *
      * @return the unknown_type
      */
     public function getLimitend() {
         return $this->limitend;
     }

     /**
      *
      * @param unknown_type $limitend
      */
     public function setLimitend($limitend) {
         $this->limitend = $limitend;
         return $this;
     }

 }
 