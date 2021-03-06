<?php

 namespace DataTable;

 use DataTable\ExceptionDataTable;

 /**
  *
  * @author hippo
  *
  */
 class DataTable extends \Nette\Application\UI\Control {

     // TODO - Insert your code here
     protected $headers = [];
     protected $rows = [];
     protected $pers_params = '';

     /**
      */
     public function __construct() {

         // TODO - Insert your code here
     }

     public function addHeads($heads = []) {
         forEach ($heads as $name => $data) {
             $this->addColumnText($name, $data);
         }
     }

     public function addColumnText($name, $data) {
         $this->headers[$name] = $data;
     }

     public function setDataSource($data) {
         $this->rows = $data;
     }

     function setPers_params($pers_params) {
         $this->pers_params = $pers_params;
     }

     public function render() {
         $this->template->dataTableID = 'DT_' . md5(time() . rand(0, 100));
         $this->template->setFile(dirname(dirname(__DIR__)) . '/controls_templates/DataTable.latte.php');
         $this->template->headers = $this->getHeaders();
         $this->template->rows = $this->getRows();
         $this->template->pers_params = $this->pers_params;
         $this->template->render();
     }

     /**
      *
      * @return the unknown_type
      */
     protected function getHeaders() {
         return $this->headers;
     }

     public function getRows() {
         return $this->rows;
     }

 }
 