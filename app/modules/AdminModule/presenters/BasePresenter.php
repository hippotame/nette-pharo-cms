<?php
namespace App\AdminModule\Presenters;

use Nette;
use App\CommonModule\Presenters\CommonPresenter;

class BasePresenter extends CommonPresenter
{

    public function __construct(Nette\Database\Context $database)
    {
        parent::__construct($database);
        $this->db = $database;
    }

    protected function startup()
    {
        parent::startup();
    }
}