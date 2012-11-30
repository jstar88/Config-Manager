<?php

require_once ('Autoloaded.php');
class SimpleManager extends Autoloaded
{
    public function __construct()
    {
        $class = parent::autoloadedCall(array($this, 'getInstance'));
        parent::__construct($class);
    }
    public function getInstance()
    {
        return new \ConfigManager\Modules\System\Simple\SimpleManager();
    }
}
