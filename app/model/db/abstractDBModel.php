<?php
namespace DB;

use DBExc\DBExeption;

class abstractDBModel
{

    protected $db;

    protected $table;

    private $data = Array();

    public $txt_table;

    protected $useTXT = false;

    protected $limitstart = null;

    protected $limitend = null;

    /**
     *
     * @param \Nette\Database\Context $database
     */
    public function __construct(\Nette\Database\Context $database)
    {
        $this->db = $database;
        $this->setTXTTable();
    }

    public function getSelection($table = null)
    {
        if (is_null($table) === false) {
            return $this->db->table($table);
        }
        if (is_null($this->table) === false) {
            return $this->db->table($this->table);
        }
        throw new \Exception('Neni zadana tabulka');
    }

    public function setTXTTable($txt_table = null)
    {
        if (is_null($this->table) === false) {
            $this->txt_table = $this->table . '_txt';
            return;
        }
        throw new DBExeption('Table TXT se musi nasetovat');
    }

    public function getAll()
    {
        return $this->getSelection()->fetchAll();

    }

    public function count() {
        return $this->getSelection()->count();
    }

    /**
     *
     * @return the unknown_type
     */
    public function getUseTXT()
    {
        return $this->useTXT;
    }

    /**
     *
     * @param unknown_type $useTXT
     */
    public function setUseTXT($useTXT)
    {
        $this->useTXT = $useTXT;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getLimitstart()
    {
        return $this->limitstart;
    }

    /**
     *
     * @param unknown_type $limitstart
     */
    public function setLimitstart($limitstart)
    {
        $this->limitstart = $limitstart;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getLimitend()
    {
        return $this->limitend;
    }

    /**
     *
     * @param unknown_type $limitend
     */
    public function setLimitend($limitend)
    {
        $this->limitend = $limitend;
        return $this;
    }
}