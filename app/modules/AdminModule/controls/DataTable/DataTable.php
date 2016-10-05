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

    public function render() {
        $this->template->setFile(dirname(dirname(__DIR__)) . '/controls_templates/DataTable.latte.php');
        $this->template->headers = $this->getHeaders();
        $this->template->rows = $this->getRows();
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