<?php

 /*
  * Pharo
  */

 namespace App\DB;

 /**
  * Description of ModulesModel
  *
  * @author hippo
  */
 class ModulesModel extends AbstractDBModel {
     public $table = 'modules';

     public function __construct(\Nette\Database\Context $database) {
         parent::__construct($database, false);
     }

 }
 