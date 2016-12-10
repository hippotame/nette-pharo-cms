<?php
namespace App\Datastores;

use Nette;
use Nette\Exception;
use Nette\Diagnostics\Debugger;

/**
 * @author hippo
 *
 */
class Datastore
{

    protected $data = Array();

    protected static $instance = false;

    protected $sessionName = 'APP.DATASTORE';

    /**
     * 
     */
    protected function __construct()
    {
        if (isset($_SESSION[$this->sessionName])) {
            $this->restore();
        } else {
            $_SESSION[$this->sessionName] = [];
        }
    }

    public static function gI()
    {
        if (self::$instance === false) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    /**
     * @param unknown $name
     * @param unknown $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param string $name
     * @return multitype:|NULL
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        
        $trace = debug_backtrace();
        trigger_error('Undefined property via __get(): 
            ' . $name . ' in ' . $trace[0]['file'] . ' on line 
            ' . $trace[0]['line'], E_USER_NOTICE);
        return null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public function __destruct()
    {
        $this->store();
    }

    public function store()
    {
        $_SESSION[$this->sessionName] = $this->data;
    }

    private function restore()
    {
        $this->data = $_SESSION[$this->sessionName];
    }

    public function clear()
    {
        $this->data = null;
        $this->store();
    }
    // use just for testing purposes
    public function getAll()
    {
        return $this->data;
    }
    

    /**
     * @param unknown $path
     */
    public function find($path) {
        
    }
}


