<?php

require_once (dirname(__file__) . str_replace('/', DIRECTORY_SEPARATOR, '/ConfigManager/Libs/splClassLoader/SplClassLoader.php'));

class Autoloaded
{
    private $class;
    public function __construct($class)
    {
        $this->class = $class;
    }
    public function __call($name, $arguments)
    {
        return self::autoloadedCall(array($this->class, $name), $arguments);
    }
    public static function __callStatic($name, $arguments)
    {
        return self::autoloadedCall(array(get_called_class(), $name), $arguments);
    }
    protected static function autoloadedCall(array $callBack, $args)
    {
        $spl = new SplClassLoader('ConfigManager');
        $spl->setIncludePath(dirname(__file__));
        $spl->register();
        $return = call_user_func_array($callBack, $args);
        $spl->unregister();
        return $return;
    }

}
